<?php

namespace myatKyawThu\LaravelApiVisibility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Router;
use myatKyawThu\LaravelApiVisibility\Contracts\ResponsePreviewerInterface;
use myatKyawThu\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use myatKyawThu\LaravelApiVisibility\Support\ErrorHandler;
use myatKyawThu\LaravelApiVisibility\Support\ResponseStructureExtractor;
use Throwable;

class PreviewController extends Controller
{
    protected RouteCollectorInterface $routeCollector;
    protected ResponsePreviewerInterface $responsePreviewer;
    protected Router $router;
    protected ErrorHandler $errorHandler;
    protected ResponseStructureExtractor $extractor;

    public function __construct(
        RouteCollectorInterface $routeCollector,
        ResponsePreviewerInterface $responsePreviewer,
        Router $router
    ) {
        $this->routeCollector    = $routeCollector;
        $this->responsePreviewer = $responsePreviewer;
        $this->router            = $router;
        $this->errorHandler      = new ErrorHandler();
        $this->extractor         = new ResponseStructureExtractor();
    }

    /** List page — just the sidebar */
    public function index()
    {
        try {
            return view('api-visibility::preview', [
                'routes'           => $this->routeCollector->getNamedRoutes(),
                'selectedRoute'    => null,
                'selectedRouteInfo'=> null,
                'analysis'         => null,
            ]);
        } catch (Throwable $e) {
            return view('api-visibility::error', ['error' => $this->errorHandler->handle($e)]);
        }
    }

    /** Detail page for one route — pure static analysis, NO HTTP execution */
    public function show(Request $request, string $routeName)
    {
        try {
            $routeInfo = $this->routeCollector->getRouteByName($routeName);

            $analysis = $this->analyse($routeInfo);

            return view('api-visibility::preview', [
                'routes'            => $this->routeCollector->getNamedRoutes(),
                'selectedRoute'     => $routeName,
                'selectedRouteInfo' => $routeInfo,
                'analysis'          => $analysis,
            ]);
        } catch (Throwable $e) {
            return view('api-visibility::error', ['error' => $this->errorHandler->handle($e)]);
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Build the full analysis array for a route — no HTTP calls.
     */
    private function analyse(?array $routeInfo): ?array
    {
        if (!$routeInfo) return null;

        $method     = strtoupper($routeInfo['methods'][0] ?? 'GET');
        $controller = $routeInfo['controller'] ?? null;
        $middleware = $routeInfo['middleware'] ?? [];

        // Detect auth requirement
        $requiresAuth = collect($middleware)->contains(fn($m) =>
            str_contains($m, 'auth') || str_contains($m, 'sanctum') || str_contains($m, 'jwt')
        );

        // Validation / request body
        $validationRules = $routeInfo['validation_rules'] ?? [];
        $examplePayload  = !empty($validationRules)
            ? $this->extractor->generateExamplePayload($validationRules)
            : null;

        // URI params
        $uriParams = $this->extractUriParams($routeInfo['uri'] ?? '');

        // Response structure from controller code
        $responses = ['success_responses' => [], 'error_responses' => [], 'resources' => []];
        if ($controller && str_contains($controller, '@')) {
            [$class, $action] = explode('@', $controller, 2);
            $responses = $this->extractor->extractFromController($class, $action);
        }

        // Build success / error JSON strings
        $successJson = null;
        $errorJson   = null;

        if (!empty($responses['success_responses'])) {
            $first = $responses['success_responses'][0];
            $successJson = [
                'status' => $first['status'],
                'body'   => $this->extractor->renderMockJson($first['body']),
            ];
        }

        if (!empty($responses['error_responses'])) {
            $errorJsons = [];
            foreach ($responses['error_responses'] as $err) {
                $errorJsons[] = [
                    'status' => $err['status'],
                    'body'   => $this->extractor->renderMockJson($err['body']),
                ];
            }
            $errorJson = $errorJsons;
        }

        return [
            'method'          => $method,
            'uri'             => $routeInfo['uri'],
            'controller'      => $controller,
            'middleware'      => $middleware,
            'requires_auth'   => $requiresAuth,
            'uri_params'      => $uriParams,
            'validation_rules'=> $validationRules,
            'example_payload' => $examplePayload,
            'success_response'=> $successJson,
            'error_responses' => $errorJson,
            'resources'       => $responses['resources'],
        ];
    }

    private function extractUriParams(string $uri): array
    {
        preg_match_all('/\{(\w+?)(\?)?\}/', $uri, $m);
        $params = [];
        foreach ($m[1] as $i => $name) {
            $params[] = ['name' => $name, 'required' => ($m[2][$i] ?? '') !== '?'];
        }
        return $params;
    }
}
