<?php

namespace myatKyawThu\LaravelApiVisibility\Tests\Unit\Core;

use Mockery;
use myatKyawThu\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use myatKyawThu\LaravelApiVisibility\Core\DocumentationGenerator;
use myatKyawThu\LaravelApiVisibility\Support\RouteGrouping;
use myatKyawThu\LaravelApiVisibility\Tests\TestCase;

class DocumentationGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $routes = [
            [
                'name' => 'api.users.index',
                'uri' => 'api/users',
                'methods' => ['GET'],
                'controller' => 'UserController@index',
                'middleware' => ['api'],
                'prefix' => 'api',
            ],
        ];

        $routeCollector = Mockery::mock(RouteCollectorInterface::class);
        $routeCollector->shouldReceive('collect')->once()->andReturn($routes);

        $routeGrouping = new RouteGrouping();

        $generator = new DocumentationGenerator($routeCollector, $routeGrouping);
        $result = $generator->generate();

        $this->assertEquals($routes, $result);
    }

    public function testGroupBy()
    {
        $routes = [
            [
                'name' => 'api.users.index',
                'uri' => 'api/users',
                'methods' => ['GET'],
                'controller' => 'UserController@index',
                'middleware' => ['api'],
                'prefix' => 'api',
            ],
            [
                'name' => 'admin.users.index',
                'uri' => 'admin/users',
                'methods' => ['GET'],
                'controller' => 'Admin\UserController@index',
                'middleware' => ['web', 'auth'],
                'prefix' => 'admin',
            ],
        ];

        $routeCollector = Mockery::mock(RouteCollectorInterface::class);
        $routeCollector->shouldReceive('collect')->once()->andReturn($routes);

        $routeGrouping = new RouteGrouping();

        $generator = new DocumentationGenerator($routeCollector, $routeGrouping);
        $result = $generator->groupBy('prefix');

        $expected = [
            'api' => [
                [
                    'name' => 'api.users.index',
                    'uri' => 'api/users',
                    'methods' => ['GET'],
                    'controller' => 'UserController@index',
                    'middleware' => ['api'],
                    'prefix' => 'api',
                ],
            ],
            'admin' => [
                [
                    'name' => 'admin.users.index',
                    'uri' => 'admin/users',
                    'methods' => ['GET'],
                    'controller' => 'Admin\UserController@index',
                    'middleware' => ['web', 'auth'],
                    'prefix' => 'admin',
                ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
