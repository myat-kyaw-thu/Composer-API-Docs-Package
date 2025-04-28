<?php

namespace Primebeyonder\LaravelApiVisibility\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Mockery;
use Primebeyonder\LaravelApiVisibility\Facades\ApiVisibility;
use Primebeyonder\LaravelApiVisibility\Tests\TestCase;

class PreviewRouteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Define a test route
        Route::get('/test', function () {
            return response()->json(['message' => 'Test route']);
        })->name('test.route');
    }

    public function testPreviewIndexRouteReturnsView()
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

        $response = $this->get(config('api-visibility.preview_route', '/preview'));

        $response->assertStatus(200);
        $response->assertViewIs('api-visibility::preview');
        $response->assertViewHas('routes');
    }

    public function testPreviewShowRouteReturnsView()
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

        ApiVisibility::shouldReceive('previewResponse')
            ->with('test.route', [])
            ->once()
            ->andReturn([
                'formatted' => '{"message": "Test route"}',
                'original' => response()->json(['message' => 'Test route']),
                'status' => 200,
                'headers' => ['Content-Type' => ['application/json']],
            ]);

        $response = $this->get(config('api-visibility.preview_route', '/preview') . '/test.route');

        $response->assertStatus(200);
        $response->assertViewIs('api-visibility::preview');
        $response->assertViewHas('routes');
        $response->assertViewHas('selectedRoute', 'test.route');
        $response->assertViewHas('result');
    }

    public function testPreviewShowRouteWithInvalidRoute()
    {
        // Mock the ApiVisibility facade
        ApiVisibility::shouldReceive('getDocumentation')
            ->once()
            ->andReturn([]);

        ApiVisibility::shouldReceive('previewResponse')
            ->with('invalid.route', [])
            ->once()
            ->andThrow(new \InvalidArgumentException("Route with name 'invalid.route' not found."));

        $response = $this->get(config('api-visibility.preview_route', '/preview') . '/invalid.route');

        $response->assertStatus(200);
        $response->assertViewIs('api-visibility::preview');
        $response->assertViewHas('error');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
