<?php

namespace Primebeyonder\LaravelApiVisibility\Core;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Primebeyonder\LaravelApiVisibility\Contracts\ResponsePreviewerInterface;
use Primebeyonder\LaravelApiVisibility\Exceptions\AuthenticationException;
use Primebeyonder\LaravelApiVisibility\Exceptions\InvalidResponseException;
use Primebeyonder\LaravelApiVisibility\Exceptions\RouteNotFoundException;
use Primebeyonder\LaravelApiVisibility\Exceptions\RouteParameterMissingException;
use Primebeyonder\LaravelApiVisibility\Exceptions\ValidationException;
use Primebeyonder\LaravelApiVisibility\Support\ErrorHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResponsePreviewer implements ResponsePreviewerInterface
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The error handler instance.
     *
     * @var \Primebeyonder\LaravelApiVisibility\Support\ErrorHandler
     */
    protected $errorHandler;

    /**
     * Create a new response previewer instance.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->errorHandler = new ErrorHandler();
    }

    /**
     * Preview a route response.
     *
     * @param string $routeName
     * @param array $parameters
     * @return array
     */
    public function preview(string $routeName, array $parameters = []): array
    {
        try {
            $route = $this->findRouteByName($routeName);

            // Check for required parameters
            $this->checkRequiredParameters($route, $parameters);

            // Check for authentication middleware
            if ($this->routeRequiresAuth($route) && !Auth::check()) {
                throw new AuthenticationException($routeName);
            }

            $request = $this->createRequest($route, $parameters);

            // Dispatch the request to the router
            $response = $this->dispatchRequest($request, $route);

            return $this->formatResponse($response);
        } catch (Throwable $exception) {
            return $this->errorHandler->handle($exception);
        }
    }

    /**
     * Find a route by its name.
     *
     * @param string $name
     * @return \Illuminate\Routing\Route
     *
     * @throws \Primebeyonder\LaravelApiVisibility\Exceptions\RouteNotFoundException
     */
    protected function findRouteByName(string $name): Route
    {
        $route = $this->router->getRoutes()->getByName($name);

        if (!$route) {
            throw new RouteNotFoundException($name);
        }

        return $route;
    }

    /**
     * Check if all required parameters are provided.
     *
     * @param \Illuminate\Routing\Route $route
     * @param array $parameters
     * @return void
     *
     * @throws \Primebeyonder\LaravelApiVisibility\Exceptions\RouteParameterMissingException
     */
    protected function checkRequiredParameters(Route $route, array $parameters): void
    {
        $uri = $route->uri();
        $requiredParams = [];

        // Extract required parameters from the URI
        preg_match_all('/\{([^?}]+)(?:\:[^}]+)?\}/', $uri, $matches);

        if (isset($matches[1]) && !empty($matches[1])) {
            $requiredParams = $matches[1];
        }

        // Check if all required parameters are provided
        $missingParams = [];
        foreach ($requiredParams as $param) {
            if (!isset($parameters[$param]) || (is_string($parameters[$param]) && trim($parameters[$param]) === '')) {
                $missingParams[] = $param;
            }
        }

        if (!empty($missingParams)) {
            throw new RouteParameterMissingException($route->getName(), $missingParams);
        }
    }

    /**
     * Check if the route requires authentication.
     *
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    protected function routeRequiresAuth(Route $route): bool
    {
        $middlewares = $route->gatherMiddleware();

        foreach ($middlewares as $middleware) {
            if ($middleware === 'auth' || strpos($middleware, 'auth:') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create a request instance for the route.
     *
     * @param \Illuminate\Routing\Route $route
     * @param array $parameters
     * @return \Illuminate\Http\Request
     */
    protected function createRequest(Route $route, array $parameters): Request
    {
        $method = $this->getRouteMethod($route);
        $uri = $this->getRouteUri($route, $parameters);

        // For non-GET requests, move parameters to request body
        if ($method !== 'GET') {
            $queryParams = [];
            $request = Request::create($uri, $method, $parameters, [], [], [], null);
        } else {
            // For GET requests, parameters are in the query string
            $request = Request::create($uri, $method, [], [], [], [], null);
        }

        // Add CSRF token for non-GET requests
        if ($method !== 'GET') {
            $request->headers->set('X-CSRF-TOKEN', csrf_token());
            $request->cookies->set('XSRF-TOKEN', encrypt(csrf_token()));

            // Mark this request as a preview request
            $request->headers->set('X-API-VISIBILITY-PREVIEW', 'true');
        }

        // Set the route on the request
        $request->setRouteResolver(function () use ($route) {
            return $route;
        });

        return $request;
    }

    /**
     * Get the HTTP method for the route.
     *
     * @param \Illuminate\Routing\Route $route
     * @return string
     */
    protected function getRouteMethod(Route $route): string
    {
        $methods = $route->methods();

        // Filter out HEAD
        $methods = array_filter($methods, function ($method) {
            return $method !== 'HEAD';
        });

        return reset($methods);
    }

    /**
     * Get the URI for the route with parameters.
     *
     * @param \Illuminate\Routing\Route $route
     * @param array $parameters
     * @return string
     */
    protected function getRouteUri(Route $route, array $parameters): string
    {
        $uri = $route->uri();
        $paramsCopy = $parameters;

        // Replace route parameters
        foreach ($paramsCopy as $key => $value) {
            if (strpos($uri, '{' . $key . '}') !== false || strpos($uri, '{' . $key . '?}') !== false) {
                $uri = str_replace(['{' . $key . '}', '{' . $key . '?}'], $value, $uri);
                unset($paramsCopy[$key]);
            }
        }

        // Add query string for GET requests
        if ($this->getRouteMethod($route) === 'GET' && !empty($paramsCopy)) {
            $uri .= '?' . http_build_query($paramsCopy);
        }

        return $uri;
    }

    /**
     * Dispatch the request to the router.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Routing\Route $route
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Primebeyonder\LaravelApiVisibility\Exceptions\InvalidResponseException
     */
    protected function dispatchRequest(Request $request, Route $route): Response
    {
        try {
            // Replace the current request with our custom request
            $currentRequest = app('request');
            app()->instance('request', $request);

            // Dispatch the request
            $response = $this->router->dispatch($request);

            // Restore the original request
            app()->instance('request', $currentRequest);

            // Handle validation exceptions
            if ($response->getStatusCode() === 422) {
                $content = json_decode($response->getContent(), true);

                if (isset($content['errors']) && is_array($content['errors'])) {
                    throw new ValidationException($content['errors']);
                }
            }

            return $response;
        } catch (Throwable $exception) {
            // Restore the original request in case of exception
            if (isset($currentRequest)) {
                app()->instance('request', $currentRequest);
            }

            // Handle Laravel's validation exception
            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                throw new ValidationException($exception->errors());
            }

            throw new InvalidResponseException(
                'An error occurred while processing the request: ' . $exception->getMessage(),
                $exception
            );
        }
    }

    /**
     * Format the response for display.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return array
     */
    protected function formatResponse(Response $response): array
    {
        $content = $response->getContent();
        $headers = $response->headers->all();
        $status = $response->getStatusCode();

        // Try to format JSON responses
        $formatted = $content;
        if ($this->isJsonResponse($response)) {
            try {
                $decoded = json_decode($content, true);
                $formatted = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } catch (Throwable $e) {
                // If we can't format it, just use the raw content
            }
        }

        return [
            'content' => $content,
            'formatted' => $formatted,
            'headers' => $headers,
            'status' => $status,
            'error' => $status >= 400,
        ];
    }

    /**
     * Determine if the response is JSON.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return bool
     */
    protected function isJsonResponse(Response $response): bool
    {
        return $response instanceof JsonResponse ||
            strpos($response->headers->get('Content-Type', ''), 'application/json') !== false;
    }
}
