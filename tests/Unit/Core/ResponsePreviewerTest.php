<?php

namespace Primebeyonder\LaravelApiVisibility\Tests\Unit\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Mockery;
use Primebeyonder\LaravelApiVisibility\Contracts\FormatterInterface;
use Primebeyonder\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use Primebeyonder\LaravelApiVisibility\Core\ResponsePreviewer;
use Primebeyonder\LaravelApiVisibility\Tests\TestCase;

class ResponsePreviewerTest extends TestCase
{
    public function testPreviewResponse()
    {
        $router = Mockery::mock(Router::class);
        $routeCollector = Mockery::mock(RouteCollectorInterface::class);

        $routeInfo = [
            'name' => 'api.users.index',
            'uri' => 'api/users',
            'methods' => ['GET'],
            'controller' => 'UserController@index',
        ];

        $routeCollector->shouldReceive('getRouteByName')
            ->with('api.users.index')
            ->once()
            ->andReturn($routeInfo);

        $route = Mockery::mock(\Illuminate\Routing\Route::class);
        $route->shouldReceive('methods')->andReturn(['GET']);
        $route->shouldReceive('uri')->andReturn('api/users');
        $route->shouldReceive('parameterNames')->andReturn([]);

        $router->shouldReceive('getRoutes->getByName')
            ->with('api.users.index')
            ->once()
            ->andReturn($route);

        $response = new Response(json_encode(['data' => 'test']), 200, ['Content-Type' => 'application/json']);

        $router->shouldReceive('dispatch')
            ->once()
            ->andReturn($response);

        $previewer = new ResponsePreviewer($router, $routeCollector);

        $formatter = Mockery::mock(FormatterInterface::class);
        $formatter->shouldReceive('canHandle')
            ->with($response)
            ->once()
            ->andReturn(true);

        $formatter->shouldReceive('format')
            ->with($response)
            ->once()
            ->andReturn(json_encode(['data' => 'test'], JSON_PRETTY_PRINT));

        $previewer->registerFormatter($formatter);

        $result = $previewer->preview('api.users.index');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('formatted', $result);
        $this->assertArrayHasKey('original', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals(200, $result['status']);
    }

    public function testPreviewResponseWithInvalidRoute()
    {
        $this->expectException(\InvalidArgumentException::class);

        $router = Mockery::mock(Router::class);
        $routeCollector = Mockery::mock(RouteCollectorInterface::class);

        $routeCollector->shouldReceive('getRouteByName')
            ->with('invalid.route')
            ->once()
            ->andReturn(null);

        $previewer = new ResponsePreviewer($router, $routeCollector);

        $previewer->preview('invalid.route');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
