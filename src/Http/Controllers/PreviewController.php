<?php

namespace Primebeyonder\LaravelApiVisibility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Router;
use Primebeyonder\LaravelApiVisibility\Contracts\ResponsePreviewerInterface;
use Primebeyonder\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use Primebeyonder\LaravelApiVisibility\Exceptions\RouteParameterMissingException;
use Primebeyonder\LaravelApiVisibility\Support\ErrorHandler;
use Throwable;

class PreviewController extends Controller
{
    /**
     * The route collector instance.
     *
     * @var \Primebeyonder\LaravelApiVisibility\Contracts\RouteCollectorInterface
     */
    protected $routeCollector;

    /**
     * The response previewer instance.
     *
     * @var \Primebeyonder\LaravelApiVisibility\Contracts\ResponsePreviewerInterface
     */
    protected $responsePreviewer;

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
     * Create a new controller instance.
     *
     * @param \Primebeyonder\LaravelApiVisibility\Contracts\RouteCollectorInterface $routeCollector
     * @param \Primebeyonder\LaravelApiVisibility\Contracts\ResponsePreviewerInterface $responsePreviewer
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function __construct(
        RouteCollectorInterface $routeCollector,
        ResponsePreviewerInterface $responsePreviewer,
        Router $router
    ) {
        $this->routeCollector = $routeCollector;
        $this->responsePreviewer = $responsePreviewer;
        $this->router = $router;
        $this->errorHandler = new ErrorHandler();
    }

    /**
     * Display the preview page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $routes = $this->routeCollector->getNamedRoutes();

            return view('api-visibility::preview', [
                'routes' => $routes,
            ]);
        } catch (Throwable $exception) {
            return view('api-visibility::error', [
                'error' => $this->errorHandler->handle($exception),
            ]);
        }
    }

    /**
     * Display the preview for a specific route.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $routeName
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $routeName)
    {
        try {
            $routes = $this->routeCollector->getNamedRoutes();
            $parameters = $request->all();

            // Get the route to check for required parameters
            $route = $this->router->getRoutes()->getByName($routeName);

            if ($route) {
                // Check for missing required parameters
                $this->checkRequiredParameters($route, $parameters);
            }

            // Handle dynamic parameters
            if ($request->has('param_key') && $request->has('param_value')) {
                $keys = $request->input('param_key', []);
                $values = $request->input('param_value', []);

                foreach ($keys as $index => $key) {
                    if (!empty($key) && isset($values[$index])) {
                        $parameters[$key] = $values[$index];
                    }
                }

                // Remove the param_key and param_value from parameters
                unset($parameters['param_key'], $parameters['param_value']);
            }

            $result = $this->responsePreviewer->preview($routeName, $parameters);

            if (isset($result['error']) && $result['error'] === true) {
                // This is an error response from our error handler
                return view('api-visibility::error', [
                    'error' => $result,
                ]);
            }

            return view('api-visibility::preview', [
                'routes' => $routes,
                'selectedRoute' => $routeName,
                'result' => $result,
            ]);
        } catch (Throwable $exception) {
            return view('api-visibility::error', [
                'error' => $this->errorHandler->handle($exception),
            ]);
        }
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
    protected function checkRequiredParameters($route, array $parameters)
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
}
