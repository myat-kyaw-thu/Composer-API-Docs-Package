<?php

namespace Primebeyonder\LaravelApiVisibility\Core;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Primebeyonder\LaravelApiVisibility\Contracts\FormatterInterface;
use Primebeyonder\LaravelApiVisibility\Contracts\ResponsePreviewerInterface;
use Primebeyonder\LaravelApiVisibility\Contracts\RouteCollectorInterface;

class ResponsePreviewer implements ResponsePreviewerInterface
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var RouteCollectorInterface
     */
    protected $routeCollector;

    /**
     * @var Collection
     */
    protected $formatters;

    /**
     * Create a new response previewer instance.
     *
     * @param Router $router
     * @param RouteCollectorInterface $routeCollector
     */
    public function __construct(Router $router, RouteCollectorInterface $routeCollector)
    {
        $this->router = $router;
        $this->routeCollector = $routeCollector;
        $this->formatters = collect([]);
    }

    /**
     * Register a formatter.
     *
     * @param FormatterInterface $formatter
     * @return $this
     */
    public function registerFormatter(FormatterInterface $formatter)
    {
        $this->formatters->push($formatter);

        return $this;
    }

    /**
     * Preview a response for a route.
     *
     * @param string $routeName
     * @param array $parameters
     * @return array
     */
    public function preview(string $routeName, array $parameters = []): array
    {
        $routeInfo = $this->routeCollector->getRouteByName($routeName);

        if (!$routeInfo) {
            throw new \InvalidArgumentException("Route with name '{$routeName}' not found.");
        }

        $route = $this->router->getRoutes()->getByName($routeName);
        $request = $this->createRequest($route, $parameters);

        // Add CSRF token to the request
        $this->addCsrfToken($request);

        // Dispatch the request to get the response
        $response = $this->router->dispatch($request);

        // Format the response if needed
        foreach ($this->formatters as $formatter) {
            if ($formatter->canHandle($response)) {
                return [
                    'formatted' => $formatter->format($response),
                    'original' => $response,
                    'status' => $response->getStatusCode(),
                    'headers' => $response->headers->all(),
                ];
            }
        }

        // Always return a consistent array structure, even when no formatter can handle it
        return [
            'formatted' => $response->getContent(),
            'original' => $response,
            'status' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
        ];
    }

    /**
     * Create a request for a route.
     *
     * @param \Illuminate\Routing\Route $route
     * @param array $parameters
     * @return Request
     */
    protected function createRequest($route, array $parameters): Request
    {
        $method = $route->methods()[0];
        $uri = $route->uri();

        // Replace route parameters
        foreach ($route->parameterNames() as $name) {
            if (isset($parameters[$name])) {
                $uri = str_replace("{{$name}}", $parameters[$name], $uri);
            } else {
                $uri = str_replace("{{$name}}", "1", $uri); // Default value
            }
        }

        $request = Request::create($uri, $method, $parameters);

        // Set JSON headers for API requests
        $request->headers->set('Accept', 'application/json');

        // For POST/PUT/PATCH requests, add the parameters as JSON
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $request->headers->set('Content-Type', 'application/json');
            $request->setContent(json_encode($parameters));
        }

        return $request;
    }

    /**
     * Add CSRF token to the request.
     *
     * @param Request $request
     * @return void
     */
    protected function addCsrfToken(Request $request): void
    {
        // Only add CSRF token for state changing methods
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Generate a token
            $token = Str::random(40);

            // Add it to the request
            $request->cookies->set('XSRF-TOKEN', $token);
            $request->headers->set('X-CSRF-TOKEN', $token);

            // For form requests, also add it as a parameter
            if ($request->headers->get('Content-Type') === 'application/x-www-form-urlencoded') {
                $params = $request->request->all();
                $params['_token'] = $token;
                $request->request->replace($params);
            }
        }
    }
}
