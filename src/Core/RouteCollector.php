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
     * Cache for validation rules extraction.
     *
     * @var array
     */
    private $validationRulesCache = [];

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
                // Filter out routes that should be excluded
                return $this->shouldIncludeRoute($route, true);
            })
            ->map(function ($route) {
                return $this->extractRouteInformation($route);
            })
            ->filter(function ($routeInfo) {
                // Only include routes that have a name (generated or explicit)
                return !empty($routeInfo['name']);
            })
            ->values()
            ->all();
    }

    /**
     * Get a specific route by name (including auto-generated names).
     *
     * @param string $name
     * @return array|null
     */
    public function getRouteByName(string $name): ?array
    {
        // Try Laravel's native name lookup first (explicit route names)
        $route = $this->router->getRoutes()->getByName($name);
        if ($route) {
            return $this->extractRouteInformation($route);
        }

        // Fall back: scan all routes and match auto-generated names.
        // We generate the name cheaply (no validation extraction) to find the route,
        // then do the full extraction only once we have a match.
        foreach ($this->router->getRoutes()->getRoutes() as $route) {
            $generatedName = $this->generateRouteName($route);
            if ($generatedName === $name) {
                return $this->extractRouteInformation($route);
            }
        }

        return null;
    }

    /**
     * Generate a route name without running validation extraction.
     * Mirrors the logic in extractRouteInformation but is cheap.
     */
    private function generateRouteName($route): ?string
    {
        $name = $route->getName();
        if ($name) return $name;

        $action     = $route->getAction();
        $controller = $action['controller'] ?? null;

        if ($controller && str_contains($controller, '@')) {
            [$controllerClass, $method] = explode('@', $controller, 2);
            return strtolower(class_basename($controllerClass)) . '.' . $method;
        }

        return null;
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
        // Don't filter by name here - we'll generate names in extractRouteInformation
        // Just check if it has a controller (API routes should have controllers)
        $action = $route->getAction();
        if (!isset($action['controller'])) {
            return false;
        }

        // Check for excluded middleware
        $excludedMiddleware = config('api-visibility.exclude_middleware', []);
        if (!empty($excludedMiddleware)) {
            $routeMiddleware = $route->gatherMiddleware();
            foreach ($excludedMiddleware as $middleware) {
                if (in_array($middleware, $routeMiddleware)) {
                    return false;
                }
            }
        }

        // Check for excluded URIs
        $excludedUris = config('api-visibility.exclude_uris', []);
        if (!empty($excludedUris)) {
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
        }

        // Check for excluded namespaces
        $excludedNamespaces = config('api-visibility.exclude_namespaces', []);
        if (!empty($excludedNamespaces)) {
            $controller = $action['controller'];
            foreach ($excludedNamespaces as $namespace) {
                if (strpos($controller, $namespace) === 0) {
                    return false;
                }
            }
        }

        // Check for excluded prefixes
        $excludedPrefixes = config('api-visibility.exclude_prefixes', []);
        if (!empty($excludedPrefixes)) {
            $prefix = $action['prefix'] ?? '';
            foreach ($excludedPrefixes as $excludedPrefix) {
                if ($prefix && strpos($prefix, $excludedPrefix) === 0) {
                    return false;
                }
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

        // Generate a name if one doesn't exist
        $name = $route->getName();
        if (!$name) {
            // Generate name from controller and method
            if ($controller && strpos($controller, '@') !== false) {
                list($controllerClass, $method) = explode('@', $controller);
                $controllerName = class_basename($controllerClass);
                $name = strtolower($controllerName) . '.' . $method;
            } else {
                // Generate from URI and method
                $uri = $route->uri();
                $methods = $route->methods();
                $method = reset($methods);
                if ($method !== 'HEAD') {
                    $name = strtolower($method) . '.' . str_replace('/', '.', $uri);
                }
            }
        }

        return [
            'name' => $name,
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
        $cacheKey = $controllerClass . '@' . $method;
        
        // Return cached result if available
        if (isset($this->validationRulesCache[$cacheKey])) {
            return $this->validationRulesCache[$cacheKey];
        }

        try {
            $reflectionMethod = new ReflectionMethod($controllerClass, $method);
            $parameters = $reflectionMethod->getParameters();

            // First, try to extract from FormRequest type hints
            foreach ($parameters as $parameter) {
                $type = $parameter->getType();
                if ($type && !$type->isBuiltin()) {
                    $className = $type->getName();
                    $rules = $this->validationExtractor->extractFromClass($className);

                    if (!empty($rules)) {
                        return $this->validationRulesCache[$cacheKey] = $rules;
                    }
                }
            }

            // If no FormRequest found, try to extract from method body
            // This handles cases where validation is done inline
            $filename = $reflectionMethod->getFileName();
            $startLine = $reflectionMethod->getStartLine();
            $endLine = $reflectionMethod->getEndLine();

            if ($filename && file_exists($filename)) {
                $fileContent = file_get_contents($filename);
                $lines = explode("\n", $fileContent);
                $methodCode = implode("\n", array_slice($lines, $startLine - 1, $endLine - $startLine + 1));

                // Look for $request->validate() or $this->validate() calls
                if (preg_match('/(?:\$request|$this)->validate\s*\(\s*\[([^\]]+)\]/s', $methodCode, $matches)) {
                    return $this->validationRulesCache[$cacheKey] = $this->parseInlineValidationRules($matches[1]);
                }
            }
        } catch (\ReflectionException $e) {
            // Silently fail if reflection fails
        }

        return $this->validationRulesCache[$cacheKey] = [];
    }

    /**
     * Parse inline validation rules from method code.
     *
     * @param string $rulesCode
     * @return array
     */
    protected function parseInlineValidationRules(string $rulesCode): array
    {
        $rules = [];
        $lines = explode("\n", $rulesCode);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line === ',') {
                continue;
            }

            // Match 'field' => 'rule|rule' or 'field' => ['rule', 'rule']
            if (preg_match('/[\'"](\w+)[\'"]\s*=>\s*(.+?)(?:,|$)/s', $line, $matches)) {
                $field = $matches[1];
                $ruleValue = trim($matches[2], " ,;");

                // Handle array format
                if (preg_match('/\[(.+?)\]/s', $ruleValue, $arrayMatches)) {
                    $ruleArray = [];
                    $items = explode(',', $arrayMatches[1]);
                    foreach ($items as $item) {
                        $item = trim($item, " '\"");
                        if (!empty($item)) {
                            $ruleArray[] = $item;
                        }
                    }
                    $rules[$field] = $ruleArray;
                } else {
                    // Handle string format
                    $rules[$field] = explode('|', $ruleValue);
                }
            }
        }

        return $rules;
    }
}
