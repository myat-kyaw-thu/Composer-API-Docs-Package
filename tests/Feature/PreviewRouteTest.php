<?php

namespace myatKyawThu\LaravelApiVisibility\Tests\Feature;

use Illuminate\Support\Facades\Route;
use myatKyawThu\LaravelApiVisibility\Tests\TestCase;

class PreviewRouteTest extends TestCase
{
    /** @test */
    public function it_displays_preview_index_page_with_routes()
    {
        $this->registerTestRoutes();

        $response = $this->get('/preview');

        $response->assertStatus(200)
            ->assertViewIs('api-visibility::preview')
            ->assertViewHas('routes');
    }

    /** @test */
    public function it_shows_preview_for_specific_route()
    {
        // Register a simple test route
        Route::get('/api/test', function () {
            return response()->json(['message' => 'Test response', 'timestamp' => now()]);
        })->name('api.test');

        $response = $this->get('/preview/api.test');

        $response->assertStatus(200)
            ->assertViewIs('api-visibility::preview')
            ->assertViewHas('routes')
            ->assertViewHas('selectedRoute', 'api.test')
            ->assertViewHas('result');
    }

    /** @test */
    public function it_handles_preview_with_route_parameters()
    {
        // Register a route with parameters
        Route::get('/api/users/{id}', function ($id) {
            return response()->json(['user_id' => $id, 'name' => 'Test User']);
        })->name('api.users.show');

        $response = $this->get('/preview/api.users.show?id=123');

        $response->assertStatus(200)
            ->assertViewIs('api-visibility::preview')
            ->assertViewHas('selectedRoute', 'api.users.show')
            ->assertViewHas('result');
    }

    /** @test */
    public function it_handles_post_request_preview()
    {
        // Register a POST route
        Route::post('/api/users', function () {
            return response()->json(['created' => true, 'id' => 1]);
        })->name('api.users.store');

        $response = $this->post('/preview/api.users.store', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200)
            ->assertViewIs('api-visibility::preview')
            ->assertViewHas('selectedRoute', 'api.users.store')
            ->assertViewHas('result');
    }

    /** @test */
    public function it_shows_error_for_invalid_route()
    {
        $response = $this->get('/preview/invalid.route');

        $response->assertStatus(200)
            ->assertViewIs('api-visibility::preview')
            ->assertViewHas('error');
    }

    /** @test */
    public function it_respects_preview_disabled_configuration()
    {
        config(['api-visibility.enable_preview' => false]);

        $response = $this->get('/preview');

        // Should return 404 when preview is disabled
        $response->assertStatus(404);
    }

    /** @test */
    public function it_respects_middleware_configuration_for_preview()
    {
        config(['api-visibility.middleware' => ['auth']]);

        $response = $this->get('/preview');

        // Should redirect to login since we're using auth middleware
        $response->assertStatus(302);
    }

    /** @test */
    public function it_handles_routes_with_validation_rules()
    {
        // Create a form request class for testing
        $this->createFormRequestClass();

        // Register a route that uses form request validation
        Route::post('/api/validated', function () {
            return response()->json(['validated' => true]);
        })->name('api.validated');

        $response = $this->post('/preview/api.validated', [
            'name' => 'Test',
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200)
            ->assertViewIs('api-visibility::preview')
            ->assertViewHas('result');
    }

    /** @test */
    public function it_formats_json_responses_correctly()
    {
        Route::get('/api/json-test', function () {
            return response()->json([
                'data' => ['id' => 1, 'name' => 'Test'],
                'meta' => ['total' => 1]
            ]);
        })->name('api.json.test');

        $response = $this->get('/preview/api.json.test');

        $response->assertStatus(200);
        
        $result = $response->viewData('result');
        $this->assertArrayHasKey('formatted', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals(200, $result['status']);
    }

    /** @test */
    public function it_handles_routes_with_different_http_methods()
    {
        Route::put('/api/users/{id}', function ($id) {
            return response()->json(['updated' => true, 'id' => $id]);
        })->name('api.users.update');

        Route::delete('/api/users/{id}', function ($id) {
            return response()->json(['deleted' => true, 'id' => $id]);
        })->name('api.users.destroy');

        // Test PUT request
        $response = $this->put('/preview/api.users.update', ['id' => 1]);
        $response->assertStatus(200)->assertViewHas('result');

        // Test DELETE request
        $response = $this->delete('/preview/api.users.destroy', ['id' => 1]);
        $response->assertStatus(200)->assertViewHas('result');
    }

    /**
     * Create a mock form request class for testing validation.
     */
    private function createFormRequestClass(): void
    {
        if (!class_exists('App\\Http\\Requests\\TestFormRequest')) {
            eval('
                namespace App\\Http\\Requests {
                    use Illuminate\\Foundation\\Http\\FormRequest;
                    
                    class TestFormRequest extends FormRequest {
                        public function authorize() { return true; }
                        public function rules() {
                            return [
                                "name" => "required|string|max:255",
                                "email" => "required|email"
                            ];
                        }
                    }
                }
            ');
        }
    }
}
