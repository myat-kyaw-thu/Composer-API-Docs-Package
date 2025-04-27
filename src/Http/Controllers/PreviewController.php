<?php

namespace Primebeyonder\LaravelApiVisibility\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Primebeyonder\LaravelApiVisibility\Facades\ApiVisibility;

class PreviewController extends Controller
{
    /**
     * Display the preview form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $routes = ApiVisibility::getDocumentation();

        return view('api-visibility::preview', [
            'routes' => $routes,
        ]);
    }

    /**
     * Preview a specific route.
     *
     * @param Request $request
     * @param string $routeName
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $routeName)
    {
        try {
            $result = ApiVisibility::previewResponse($routeName, $request->all());

            return view('api-visibility::preview', [
                'routes' => ApiVisibility::getDocumentation(),
                'selectedRoute' => $routeName,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return view('api-visibility::preview', [
                'routes' => ApiVisibility::getDocumentation(),
                'selectedRoute' => $routeName,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
