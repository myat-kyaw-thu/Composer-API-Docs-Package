<?php

namespace myatKyawThu\LaravelApiVisibility\Core;

use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;
use myatKyawThu\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use myatKyawThu\LaravelApiVisibility\Support\ValidationExtractor;

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
     * Get all named routes.
     *
     * @return array
     */
    public function getNamedRoutes(): array
    {
        return collect($this->router->getRoutes()->getRoutes())
            ->filter(function ($route) {
                // Only include routes with names
                return $route->getName() !== null;
            })
            ->filter(function ($route) {
                // Filter out routes that should be excluded
                return $this->shouldIncludeRoute($route, true);
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
     * @param bool $forPreview Whether this check is for preview (less strict)
     * @return bool
     */
    protected function shouldIncludeRoute($route, bool $forPreview = false): bool
    {
        // For preview, we only need named routes
        if (!$route->getName()) {
            return false;
        }

        // Check for excluded middleware
        $excludedMiddleware = config('api-visibility.exclude_middleware', []);
        foreach ($excludedMiddleware as $middleware) {
            if (in_array($middleware, $route->gatherMiddleware())) {
                return false;
            }
        }

        // Check for excluded URIs
        $excludedUris = config('api-visibility.exclude_uris', []);
        $uri = $route->uri();

        // Check for exact URI matches
        if (in_array($uri, $excludedUris)) {
            return false;
        }

        // Check for URI patterns with wildcards
        foreach ($excludedUris as $excludedUri) {
            // Convert route pattern to regex
            if (strpos($excludedUri, '{') !== false) {
                $pattern = preg_replace('/\{[^\}]+\}/', '[^/]+', $excludedUri);
                $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';

                if (preg_match($pattern, $uri)) {
                    return false;
                }
            }
        }

        // Check for excluded namespaces
        $action = $route->getAction();
        $excludedNamespaces = config('api-visibility.exclude_namespaces', []);

        if (isset($action['controller'])) {
            foreach ($excludedNamespaces as $namespace) {
                if (strpos($action['controller'], $namespace) === 0) {
                    return false;
                }
            }
        }

        // Check for excluded prefixes
        $excludedPrefixes = config('api-visibility.exclude_prefixes', []);
        $prefix = $action['prefix'] ?? '';

        foreach ($excludedPrefixes as $excludedPrefix) {
            if ($prefix && strpos($prefix, $excludedPrefix) === 0) {
                return false;
            }
        }

        // Check for package routes
        if (!$forPreview && $this->isPackageRoute($route)) {
            return false;
        }

        // Check for framework routes
        if (!$forPreview && $this->isFrameworkRoute($route)) {
            return false;
        }

        // Check for third-party routes filtering
        if (!$forPreview && $this->isThirdPartyRoute($route)) {
            // Check if this third-party route is explicitly allowed
            if ($this->isAllowedThirdPartyRoute($route)) {
                return true;
            }
            
            // Check the global third-party inclusion setting
            $includeThirdParty = config('api-visibility.include_third_party_routes', false);
            if (!$includeThirdParty) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a route belongs to the API Visibility package.
     *
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    protected function isPackageRoute($route): bool
    {
        $action = $route->getAction();

        // Check controller namespace
        if (isset($action['controller']) && strpos($action['controller'], 'myatKyawThu\\LaravelApiVisibility\\') === 0) {
            return true;
        }

        // Check route name
        $name = $route->getName();
        if ($name && (strpos($name, 'api-visibility.') === 0)) {
            return true;
        }

        // Check URI
        $uri = $route->uri();
        $packageUris = ['docs', 'preview', 'preview/{routeName}'];

        foreach ($packageUris as $packageUri) {
            $pattern = preg_replace('/\{[^\}]+\}/', '[^/]+', $packageUri);
            $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';

            if (preg_match($pattern, $uri)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a route is a Laravel framework route.
     *
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    protected function isFrameworkRoute($route): bool
    {
        $action = $route->getAction();

        // Check for framework controllers
        if (isset($action['controller'])) {
            $frameworkNamespaces = [
                'Illuminate\\',
                'Laravel\\',
                'App\\Http\\Controllers\\Auth\\',
            ];

            foreach ($frameworkNamespaces as $namespace) {
                if (strpos($action['controller'], $namespace) === 0) {
                    return true;
                }
            }
        }

        // Check for common framework routes
        $frameworkRouteNames = [
            'login',
            'logout',
            'register',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'password.confirm',
            'verification.notice',
            'verification.verify',
            'verification.resend',
            'home',
            'sanctum.',
        ];

        $name = $route->getName();
        if ($name) {
            foreach ($frameworkRouteNames as $frameworkRoute) {
                if ($name === $frameworkRoute || strpos($name, $frameworkRoute) === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if a route belongs to a third-party package.
     *
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    protected function isThirdPartyRoute($route): bool
    {
        $controllerNamespace = $this->getControllerNamespace($route);
        
        if (!$controllerNamespace) {
            return false;
        }

        // Get third-party namespaces from config
        $thirdPartyNamespaces = config('api-visibility.third_party_namespaces', []);
        
        // Check if controller namespace starts with any third-party namespace
        foreach ($thirdPartyNamespaces as $namespace) {
            if (strpos($controllerNamespace, $namespace) === 0) {
                return true;
            }
        }

        // Check if it's not in the main application namespace (App\)
        if (strpos($controllerNamespace, 'App\\') !== 0 && 
            strpos($controllerNamespace, 'myatKyawThu\\LaravelApiVisibility\\') !== 0) {
            // Additional check for vendor packages by looking for common vendor patterns
            $vendorPatterns = [
                '\\Vendor\\',
                '\\Package\\',
                '\\Plugin\\',
            ];
            
            foreach ($vendorPatterns as $pattern) {
                if (strpos($controllerNamespace, $pattern) !== false) {
                    return true;
                }
            }
            
            // If namespace doesn't start with App\ and contains backslashes, likely third-party
            if (substr_count($controllerNamespace, '\\') >= 2) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a third-party route is explicitly allowed.
     *
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    protected function isAllowedThirdPartyRoute($route): bool
    {
        $controllerNamespace = $this->getControllerNamespace($route);
        
        if (!$controllerNamespace) {
            return false;
        }

        // Get allowed third-party namespaces from config
        $allowedNamespaces = config('api-visibility.allowed_third_party_namespaces', []);
        
        // Check if controller namespace starts with any allowed namespace
        foreach ($allowedNamespaces as $namespace) {
            if (strpos($controllerNamespace, $namespace) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract the controller namespace from a route.
     *
     * @param \Illuminate\Routing\Route $route
     * @return string|null
     */
    protected function getControllerNamespace($route): ?string
    {
        $action = $route->getAction();
        
        if (!isset($action['controller'])) {
            return null;
        }

        $controller = $action['controller'];
        
        // Handle controller@method format
        if (strpos($controller, '@') !== false) {
            $controller = explode('@', $controller)[0];
        }

        // Handle callable array format [Controller::class, 'method']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        // Handle closure routes
        if ($controller instanceof \Closure) {
            return null;
        }

        try {
            // Use reflection to get the actual namespace
            $reflectionClass = new ReflectionClass($controller);
            return $reflectionClass->getNamespaceName();
        } catch (\ReflectionException $e) {
            // If reflection fails, try to extract namespace from string
            $lastBackslash = strrpos($controller, '\\');
            if ($lastBackslash !== false) {
                return substr($controller, 0, $lastBackslash);
            }
        }

        return null;
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
            'is_third_party' => $this->isThirdPartyRoute($route),
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
