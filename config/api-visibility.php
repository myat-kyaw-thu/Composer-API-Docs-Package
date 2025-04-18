<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Documentation Route
    |--------------------------------------------------------------------------
    | The URI where static API docs will be served.
    */
    'docs_route' => env('API_VISIBILITY_DOCS_ROUTE', '/docs'),

    /*
    |--------------------------------------------------------------------------
    | Preview Route
    |--------------------------------------------------------------------------
    | The URI prefix for live previews.
    */
    'preview_route' => env('API_VISIBILITY_PREVIEW_ROUTE', '/preview'),

    /*
    |--------------------------------------------------------------------------
    | Enable Live Preview
    |--------------------------------------------------------------------------
    | Toggle live-preview functionality on/off (e.g. disable in production).
    */
    'enable_preview' => env('API_VISIBILITY_ENABLE_PREVIEW', true),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    | The middleware stack to apply to both docs and preview routes.
    */
    'middleware' => ['web'],
];
