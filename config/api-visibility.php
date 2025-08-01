<?php

use myatKyawThu\LaravelApiVisibility\Core\Formatter\JsonFormatter;

return [
    /*
    |--------------------------------------------------------------------------
    | API Visibility Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Laravel API Visibility package.
    |
    */

    // Enable or disable the API documentation
    'enabled' => env('API_VISIBILITY_ENABLED', true),

    // Enable or disable the API response preview feature
    'enable_preview' => env('API_VISIBILITY_PREVIEW_ENABLED', true),

    // Route prefix for the documentation and preview
    'route_prefix' => env('API_VISIBILITY_ROUTE_PREFIX', ''),

    // Middleware for the documentation and preview routes
    'middleware' => [
        'web',
    ],

    // Exclude routes with these middleware
    'exclude_middleware' => [
        'auth.basic',
    ],

    // Exclude routes with these URIs
    'exclude_uris' => [
        '/',          // Default home route
        'docs',       // Documentation routes
        'preview',    // Documentation preview
        'preview/{routeName}', // Documentation preview with route name
        '_ignition/health-check',
        '_ignition/execute-solution',
        '_ignition/update-config',
        'sanctum/csrf-cookie',
        'livewire/upload-file',
        'livewire/livewire.js',
        'livewire/livewire.js.map',
    ],

    // Exclude routes with these prefixes
    'exclude_prefixes' => [
        '_debugbar',
        '_ignition',
    ],

    // Exclude routes with controllers in these namespaces
    'exclude_namespaces' => [
        'Laravel\Sanctum',
        'Laravel\Fortify',
        'Laravel\Jetstream',
        'Laravel\Horizon',
        'Laravel\Nova',
    ],

    // Include third-party package routes (default: false)
    'include_third_party_routes' => env('API_VISIBILITY_INCLUDE_THIRD_PARTY', false),

    // Allowed third-party namespaces (these will be included even when third-party filtering is enabled)
    'allowed_third_party_namespaces' => [
        // Example: 'MyCompany\\MyPackage\\',
    ],

    // Common third-party package namespaces to exclude (when include_third_party_routes is false)
    'third_party_namespaces' => [
        'Filament\\',
        'Livewire\\',
        'Laravel\\Sanctum\\',
        'Laravel\\Fortify\\',
        'Laravel\\Jetstream\\',
        'Laravel\\Horizon\\',
        'Laravel\\Nova\\',
        'Laravel\\Telescope\\',
        'Spatie\\',
        'Barryvdh\\',
        'Facade\\Ignition\\',
        'Nunomaduro\\Collision\\',
    ],

    // Custom formatters for response preview
    'formatters' => [
        'json' => JsonFormatter::class,
        // 'html' => HtmlFormatter::class,
    ],
];
