<?php

namespace Primebeyonder\LaravelApiVisibility\Core;

use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;
use Primebeyonder\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use Primebeyonder\LaravelApiVisibility\Support\ValidationExtractor;

class RouteCollector implements RouteCollectorInterface
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var ValidationExtractor
     */
    protected $validationExtractor;

    /**
     * Create a new route collector instance.
     *
     * @param Router $router
     * @param ValidationExtractor $validationExtractor
     */
    public function __construct(Router $router, ValidationExtractor $validationExtractor)
    {
        $this->router = $router;
        $this->validationExtractor = $validationExtractor;
    }

    /**
     * Collect all API routes.
     *
     * @return array
     */
    public function collect(): array
    {
        return collect($this->router->getRoutes()->getRoutes())
            ->filter(function ($route) {
                // Filter out non-API routes or routes that should be excluded
                return $this->shouldIncludeRoute($route);
            })
            ->map(function ($route) {
                return $this->extractRouteInformation($route);
            })
            ->values()
            ->all();
    }

    /**
     * Get a specific route by name.
     *
     * @param string $name
     * @return array|null
     */
    public function getRouteByName(string $name): ?array
    {
        $route = $this->router->getRoutes()->getByName($name);

        if (!$route) {
            return null;
        }

        return $this->extractRouteInformation($route);
    }

    /**
     * Determine if a route should be included in the documentation.
     *
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    protected function shouldIncludeRoute($route): bool
    {
        // Exclude internal routes or routes without names
        if (!$route->getName()) {
            return false;
        }

        // Exclude routes with specific middleware (can be configured)
        $excludedMiddleware = config('api-visibility.exclude_middleware', []);
        foreach ($excludedMiddleware as $middleware) {
            if (in_array($middleware, $route->gatherMiddleware())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extract information from a route.
     *
     * @param \Illuminate\Routing\Route $route
     * @return array
     */
    protected function extractRouteInformation($route): array
    {
        $action = $route->getAction();
        $controller = $action['controller'] ?? null;
        $middleware = $route->gatherMiddleware();
        $validationRules = [];

        // Extract controller and method
        if ($controller && strpos($controller, '@') !== false) {
            list($controllerClass, $method) = explode('@', $controller);

            // Extract validation rules from FormRequest if used
            $validationRules = $this->extractValidationRules($controllerClass, $method);
        }

        return [
            'name' => $route->getName(),
            'uri' => $route->uri(),
            'methods' => $route->methods(),
            'controller' => $controller,
            'middleware' => $middleware,
            'validation_rules' => $validationRules,
            'prefix' => $action['prefix'] ?? null,
            'namespace' => $action['namespace'] ?? null,
        ];
    }

    /**
     * Extract validation rules from a controller method.
     *
     * @param string $controllerClass
     * @param string $method
     * @return array
     */
    protected function extractValidationRules(string $controllerClass, string $method): array
    {
        try {
            $reflectionMethod = new ReflectionMethod($controllerClass, $method);
            $parameters = $reflectionMethod->getParameters();

            foreach ($parameters as $parameter) {
                $type = $parameter->getType();
                if ($type && !$type->isBuiltin()) {
                    $className = $type->getName();
                    $rules = $this->validationExtractor->extractFromClass($className);

                    if (!empty($rules)) {
                        return $rules;
                    }
                }
            }
        } catch (\ReflectionException $e) {
            // Silently fail if reflection fails
        }

        return [];
    }
}
