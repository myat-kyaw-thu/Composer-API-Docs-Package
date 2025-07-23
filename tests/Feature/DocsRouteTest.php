<?php

namespace myatKyawThu\LaravelApiVisibility\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Mockery;
use myatKyawThu\LaravelApiVisibility\Facades\ApiVisibility;
use myatKyawThu\LaravelApiVisibility\Tests\TestCase;

class DocsRouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Define a test route
        Route::get('/test', function () {
            return response()->json(['message' => 'Test route']);
        })->name('test.route');
    }

    public function testDocsRouteReturnsView()
    {
        // Mock the ApiVisibility facade
        ApiVisibility::shouldReceive('getDocumentation')
            ->once()
            ->andReturn([
                [
                    'name' => 'test.route',
                    'uri' => 'test',
                    'methods' => ['GET'],
                    'controller' => null,
                    'middleware' => [],
                    'validation_rules' => [],
                    'prefix' => null,
                ]
            ]);

        $response = $this->get(config('api-visibility.docs_route', '/docs'));

        $response->assertStatus(200);
        $response->assertViewIs('api-visibility::docs');
        $response->assertViewHas('routes');
        $response->assertViewHas('groupedRoutes');
    }

    public function testDocsRouteWithMiddleware()
    {
        // Set middleware that should block access
        config(['api-visibility.middleware' => ['auth']]);

        $response = $this->get(config('api-visibility.docs_route', '/docs'));

        // Should redirect to login since we're using auth middleware
        $response->assertStatus(302);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
