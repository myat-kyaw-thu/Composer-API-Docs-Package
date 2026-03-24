# Changelog

All notable changes to `laravel-api-visibility` are documented here.

---

## [Unreleased]

---

## [1.1.0] — 2026-03-24

### Added
- `ResponseStructureExtractor` — static controller analysis via PHP Reflection. Parses every `response()->json(...)` call in a controller method using a recursive balanced-bracket tokeniser. No HTTP execution. Results cached per `Class@method`.
- `/preview` page completely rewritten. Now shows: HTTP method, full URL, controller, middleware, auth detection, URI parameters, request body fields with rule tags, example payload, success response structure, error responses with status codes, and detected API Resources.
- Auto-generated route names for unnamed routes in `controllerclass.method` format (e.g. `authcontroller.register`). These work as `/preview/{routeName}` URLs.
- `getRouteByName()` fallback scan — finds auto-generated names without running validation extraction on every route (uses cheap `generateRouteName()` helper).
- Postman Collection v2.1 JSON export on the `/docs` page. Downloads a file importable into Postman, Insomnia, and compatible API clients. Includes base URL from `APP_URL`, auth headers, request body with example values, path variables, and Bearer token variable.
- `ValidationExtractor` now uses `ReflectionClass::newInstanceWithoutConstructor()` to safely instantiate FormRequest classes without triggering constructor dependencies.
- `ValidationExtractor::formatRules()` now handles `Rule` objects by converting them to class names via `class_basename()`.

### Changed
- `PreviewController` rewritten — `show()` performs only static analysis via `ResponseStructureExtractor`. `ResponsePreviewerInterface` is no longer called on page load.
- `/docs` and `/preview` UI rewritten with dark GitHub-style theme. Sidebar in preview is sticky; main content scrolls freely.
- `DocumentationGenerator` — removed `RouteGrouping` class dependency, replaced with `collect()->groupBy()` inline.
- `RouteCollector::getNamedRoutes()` — validation rules extraction is cached per `Class@method` to avoid re-parsing on repeated calls.

### Removed
- `src/Support/RouteGrouping.php` — replaced by inline `collect()->groupBy()` in `DocumentationGenerator`.

### Fixed
- `/docs` page crash: `Failed to open stream: RouteGrouping.php` — caused by deleted class still being referenced.
- `/preview` page `Undefined variable $selectedRouteInfo` error.
- Blade template error `Undefined constant "token"` — `{{token}}` Postman variable strings now escaped as `@{{token}}`.
- `ValidationExtractor` crash when FormRequest constructor requires injected dependencies.
- `ValidationExtractor` crash when validation rules contain `Rule` objects instead of strings.

---

## [1.0.0] — Initial release

### Added
- `RouteCollector` — collects and filters application routes. Excludes framework routes (`Illuminate\*`, `Laravel\*`), package-internal routes, and configurable third-party namespaces.
- `DocumentationGenerator` — groups routes by URI prefix for display.
- `ResponsePreviewer` — executes routes and returns formatted responses.
- `ValidationExtractor` — extracts validation rules from FormRequest classes.
- `JsonFormatter` and `MarkdownFormatter` — response formatters implementing `FormatterInterface`.
- `/docs` route — displays all collected routes.
- `/preview/{routeName}` route — previews a specific route.
- `EnsureDevEnvironment` middleware — blocks access in production.
- `BypassCsrfForPreview` middleware — allows preview requests without CSRF token.
- `ApiVisibility` facade via `FacadeManager`.
- Custom exception hierarchy: `ApiVisibilityException`, `AuthenticationException`, `InvalidResponseException`, `RouteNotFoundException`, `RouteParameterMissingException`, `ValidationException`.
- `ErrorHandler` support class for consistent error formatting.
- Config file `config/api-visibility.php` with full environment variable support.
- Publishable config (`--tag=api-visibility-config`) and views (`--tag=api-visibility-views`).
- Auto-discovery via Laravel's package discovery (`extra.laravel` in `composer.json`).
- Third-party route filtering with `include_third_party_routes`, `allowed_third_party_namespaces`, and `third_party_namespaces` config keys.
