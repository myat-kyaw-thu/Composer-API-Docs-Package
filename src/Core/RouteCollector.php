<?php

namespace Primebeyonder\LaravelApiVisibility\Core;

use Illuminate\Routing\Router;
use ReflectionClass;
use ReflectionMethod;
use Primebeyonder\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use Primebeyonder\LaravelApiVisibility\Support\ValidationExtractor;

class RouteCollector implements RouteCollectorInterface
{
    protected Router $router;
    protected ValidationExtractor $validationExtractor;

    public function __construct(Router $router, ValidationExtractor $validationExtractor)
    {
        $this->router = $router;
        $this->validationExtractor = $validationExtractor;
    }

    public function collect(): array
    {
        return collect($this->router->getRoutes()->getRoutes())
            ->filter(fn ($route) => $this->shouldIncludeRoute($route))
            ->map(fn ($route) => $this->extractRouteInformation($route))
            ->values()
            ->all();
    }

    public function getRouteByName(string $name): ?array
    {
        $route = $this->router->getRoutes()->getByName($name);
        return $route ? $this->extractRouteInformation($route) : null;
    }

    protected function shouldIncludeRoute($route): bool
    {
        if (!$route->getName()) {
            return false;
        }

        $uri = $route->uri();
        $excludedUris = config('api-visibility.exclude_uris', ['/', 'docs', 'preview', "preview/{routeName}"]);
        $excludedPatterns = config('api-visibility.exclude_uri_patterns', ['/^storage\/.*/']);

        // Check exact URI matches
        if (in_array($uri, $excludedUris)) {
            return false;
        }

        // Check regex pattern matches
        foreach ($excludedPatterns as $pattern) {
            if (preg_match($pattern, $uri)) {
                return false;
            }
        }

        // Check excluded middleware
        $excludedMiddleware = config('api-visibility.exclude_middleware', []);
        foreach ($excludedMiddleware as $middleware) {
            if (in_array($middleware, $route->gatherMiddleware())) {
                return false;
            }
        }

        return true;
    }

    protected function extractRouteInformation($route): array
    {
        $action = $route->getAction();
        $controller = $action['controller'] ?? null;
        $validationRules = [];

        if ($controller && str_contains($controller, '@')) {
            [$controllerClass, $method] = explode('@', $controller);
            $validationRules = $this->extractValidationRules($controllerClass, $method);
        }

        return [
            'name' => $route->getName(),
            'uri' => $route->uri(),
            'methods' => $route->methods(),
            'controller' => $controller,
            'middleware' => $route->gatherMiddleware(),
            'validation_rules' => $validationRules,
            'prefix' => $action['prefix'] ?? null,
            'namespace' => $action['namespace'] ?? null,
        ];
    }

    protected function extractValidationRules(string $controllerClass, string $method): array
    {
        try {
            $reflectionMethod = new ReflectionMethod($controllerClass, $method);

            foreach ($reflectionMethod->getParameters() as $parameter) {
                if (($type = $parameter->getType()) && !$type->isBuiltin()) {
                    $rules = $this->validationExtractor->extractFromClass($type->getName());
                    if (!empty($rules)) return $rules;
                }
            }
        } catch (\ReflectionException) {
            // Silence reflection exceptions
        }

        return [];
    }
}
