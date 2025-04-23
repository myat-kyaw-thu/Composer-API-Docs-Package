<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Documentation Route
    |--------------------------------------------------------------------------
    |
    | This is the URI path where API documentation will be accessible from.
    |
    */
    'docs_route' => '/docs',

    /*
    |--------------------------------------------------------------------------
    | Preview Route
    |--------------------------------------------------------------------------
    |
    | This is the URI path where API response previews will be accessible from.
    |
    */
    'preview_route' => '/preview',

    /*
    |--------------------------------------------------------------------------
    | Enable Preview
    |--------------------------------------------------------------------------
    |
    | Determines whether the preview functionality is enabled.
    |
    */
    'enable_preview' => true,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | The middleware that will be applied to the documentation and preview routes.
    |
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Allow in Production
    |--------------------------------------------------------------------------
    |
    | Determines whether API Visibility is enabled in production environment.
    | It's recommended to disable this in production for security reasons.
    |
    */
    'allow_in_production' => false,

        /*
    |--------------------------------------------------------------------------
    | Route Exclusion Settings
    |--------------------------------------------------------------------------
    |
    | Configure which routes should be excluded from API documentation
    */

    'exclude_middleware' => [
        'auth',
        'auth:api',
    ],

    'exclude_uris' => [
        '/',          // Default home route
        'docs',       // Documentation routes
        'preview',    // Documentation preview
        "preview/{routeName}", // Documentation preview with route name
    ],

    'exclude_uri_patterns' => [
        '/^storage\/.*/',    // Storage routes
    ],

];
