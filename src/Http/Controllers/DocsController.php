<?php

namespace Primebeyonder\LaravelApiVisibility\Http\Controllers;

use Illuminate\Routing\Controller;
use Primebeyonder\LaravelApiVisibility\Facades\ApiVisibility;

class DocsController extends Controller
{
    /**
     * Display the API documentation.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $routes = ApiVisibility::getDocumentation();

        // Group routes by prefix for better organization
        $groupedRoutes = collect($routes)->groupBy(function ($route) {
            return $route['prefix'] ?? 'api';
        })->toArray();

        return view('api-visibility::docs', [
            'routes' => $routes,
            'groupedRoutes' => $groupedRoutes,
        ]);
    }
}
