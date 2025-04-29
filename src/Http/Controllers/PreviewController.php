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
            // Get all request parameters
            $parameters = $request->all();

            // Handle dynamic parameters if they exist
            if ($request->has('param_key') && $request->has('param_value')) {
                $paramKeys = $request->input('param_key');
                $paramValues = $request->input('param_value');

                // Remove the param_key and param_value arrays from parameters
                unset($parameters['param_key']);
                unset($parameters['param_value']);

                // Add dynamic parameters
                for ($i = 0; $i < count($paramKeys); $i++) {
                    if (!empty($paramKeys[$i]) && isset($paramValues[$i])) {
                        $parameters[$paramKeys[$i]] = $paramValues[$i];
                    }
                }
            }

            $result = ApiVisibility::previewResponse($routeName, $parameters);

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
