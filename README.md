# Laravel API Visibility

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/myat-kyaw-thu/laravel-api-visibility)](https://packagist.org/packages/myat-kyaw-thu/laravel-api-visibility)
[![PHP](https://img.shields.io/badge/PHP-%5E8.0-blue)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-8--12-red)](https://laravel.com)

A developer-focused Laravel package that automatically generates API route documentation and provides static endpoint analysis. Designed as a lightweight alternative to Swagger — no annotations, no YAML, no configuration required to get started.

---

## Requirements

- PHP `^8.0`
- Laravel `^8.0 | ^9.0 | ^10.0 | ^11.0 | ^12.0`

---

## Installation

```bash
composer require myat-kyaw-thu/laravel-api-visibility
```

The package auto-registers via Laravel's package discovery. No manual provider registration needed.

### Publish config (optional)

```bash
php artisan vendor:publish --tag=api-visibility-config
```

### Publish views (optional)

```bash
php artisan vendor:publish --tag=api-visibility-views
```

---

## Routes

The package registers two routes automatically:

| URL | Description |
|-----|-------------|
| `/docs` | Route documentation overview |
| `/preview/{routeName}` | Static endpoint analysis for a specific route |

Both routes are protected by `EnsureDevEnvironment` middleware — they only work when `APP_ENV` is not `production`.

---

## /docs page

Displays all collected application routes in a dark compact table UI, grouped by URI prefix.

Features:
- Filter by route, controller, HTTP method, or auth requirement
- Per-row "Details" link to the `/preview` page
- Per-row "Copy URL" button
- **Export JSON** button — downloads a Postman Collection v2.1 file importable into Postman, Insomnia, and other API clients

The export includes:
- App name and base URL from `APP_NAME` / `APP_URL` env
- All routes grouped into Postman folders by URI prefix
- Per-request headers (`Accept`, `Content-Type`, `Authorization` if auth required)
- Request body with example values derived from validation rules
- Path variable definitions for `{param}` segments
- Bearer token variable `{{token}}` pre-wired for auth routes

---

## /preview page

Displays a detailed static analysis of a single endpoint. No HTTP requests are executed.

The sidebar lists all routes. Selecting one shows:

- HTTP method + full URL
- Controller class and method
- Middleware list
- Auth requirement (detected from `auth`, `sanctum`, `jwt` middleware)
- URI parameters (from `{param}` segments)
- Request body fields with validation rule tags (from FormRequest classes)
- Example request payload (JSON, with realistic values based on field names)
- Success response structure (parsed from `response()->json(...)` in the controller)
- Error responses with HTTP status codes
- API Resources detected in the controller method

### How static analysis works

The `ResponseStructureExtractor` reads the controller source file via PHP Reflection, extracts the method body, and parses every `response()->json(...)` call using a recursive balanced-bracket parser. No HTTP calls are made. Results are cached per `Class@method` within the request lifecycle.

The `ValidationExtractor` instantiates FormRequest classes via `ReflectionClass::newInstanceWithoutConstructor()` and calls `rules()` to extract validation rules without triggering constructor dependencies.

---

## Configuration

File: `config/api-visibility.php`

```php
return [
    // Master switch
    'enabled' => env('API_VISIBILITY_ENABLED', true),

    // Enable /preview routes
    'enable_preview' => env('API_VISIBILITY_PREVIEW_ENABLED', true),

    // Route prefix (default: no prefix — /docs and /preview at root)
    'route_prefix' => env('API_VISIBILITY_ROUTE_PREFIX', ''),

    // Middleware applied to /docs and /preview routes
    'middleware' => ['web'],

    // Exclude routes that use any of these middleware
    'exclude_middleware' => ['auth.basic'],

    // Exclude these exact URIs (supports {param} wildcards)
    'exclude_uris' => [
        '/',
        'docs',
        'preview',
        'preview/{routeName}',
        '_ignition/health-check',
        'sanctum/csrf-cookie',
    ],

    // Exclude routes whose prefix starts with these strings
    'exclude_prefixes' => ['_debugbar', '_ignition'],

    // Exclude routes from these controller namespaces
    'exclude_namespaces' => [
        'Laravel\Sanctum',
        'Laravel\Fortify',
        'Laravel\Jetstream',
        'Laravel\Horizon',
        'Laravel\Nova',
    ],

    // Include third-party package routes (default: false)
    'include_third_party_routes' => env('API_VISIBILITY_INCLUDE_THIRD_PARTY', false),

    // Always include these namespaces even when third-party filtering is on
    'allowed_third_party_namespaces' => [],

    // Namespaces treated as third-party (excluded when include_third_party_routes is false)
    'third_party_namespaces' => [
        'Filament\\', 'Livewire\\', 'Spatie\\', 'Barryvdh\\',
        'Laravel\\Sanctum\\', 'Laravel\\Fortify\\', 'Laravel\\Telescope\\',
    ],

    // Response formatters
    'formatters' => [
        'json' => \myatKyawThu\LaravelApiVisibility\Core\Formatter\JsonFormatter::class,
    ],
];
```

### Environment variables

```env
API_VISIBILITY_ENABLED=true
API_VISIBILITY_PREVIEW_ENABLED=true
API_VISIBILITY_ROUTE_PREFIX=
API_VISIBILITY_INCLUDE_THIRD_PARTY=false
```

---

## Route naming

Routes with an explicit `.name()` are used as-is. Routes without a name get an auto-generated name in the format `{controllername}.{method}` (e.g. `authcontroller.register`). These auto-generated names work with the `/preview/{routeName}` URL.

---

## How routes are collected

`RouteCollector` scans all registered Laravel routes and applies these filters in order:

1. Must have a controller (closure routes are excluded)
2. Not in `exclude_middleware` list
3. Not in `exclude_uris` list
4. Not in `exclude_namespaces` list
5. Not in `exclude_prefixes` list
6. Not a package-internal route (`myatKyawThu\LaravelApiVisibility\*`)
7. Not a Laravel framework route (`Illuminate\*`, `Laravel\*`)
8. Not a third-party route (unless `include_third_party_routes` is true or namespace is in `allowed_third_party_namespaces`)

---

## Facade

```php
use myatKyawThu\LaravelApiVisibility\Facades\ApiVisibility;

$routes = ApiVisibility::getDocumentation();
```

---

## Running tests

```bash
composer test

# With HTML coverage report (output in /coverage)
composer test-coverage
```

---

## License

MIT — see [LICENSE](LICENSE).
