<?php

namespace myatKyawThu\LaravelApiVisibility;

use Illuminate\Support\ServiceProvider;
use myatKyawThu\LaravelApiVisibility\Contracts\DocumentationGeneratorInterface;
use myatKyawThu\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use myatKyawThu\LaravelApiVisibility\Contracts\ResponsePreviewerInterface;
use myatKyawThu\LaravelApiVisibility\Core\DocumentationGenerator;
use myatKyawThu\LaravelApiVisibility\Core\RouteCollector;
use myatKyawThu\LaravelApiVisibility\Core\ResponsePreviewer;
use myatKyawThu\LaravelApiVisibility\Http\Middleware\BypassCsrfForPreview;
use myatKyawThu\LaravelApiVisibility\Support\ErrorHandler;

class ApiVisibilityServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/api-visibility.php',
            'api-visibility'
        );

        // Bind interfaces to implementations
        $this->app->bind(RouteCollectorInterface::class, RouteCollector::class);
        $this->app->bind(DocumentationGeneratorInterface::class, DocumentationGenerator::class);
        $this->app->bind(ResponsePreviewerInterface::class, ResponsePreviewer::class);

        // Register the error handler
        $this->app->singleton(ErrorHandler::class, function () {
            return new ErrorHandler();
        });

        // Register the facade
        $this->app->singleton('api-visibility', function ($app) {
            return new FacadeManager(
                $app->make(DocumentationGeneratorInterface::class),
                $app->make(ResponsePreviewerInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/api-visibility.php' => config_path('api-visibility.php'),
        ], 'api-visibility-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/api-visibility'),
        ], 'api-visibility-views');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'api-visibility');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/docs.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/preview.php');

        // Register middleware
        $this->app['router']->aliasMiddleware('api-visibility-bypass-csrf', BypassCsrfForPreview::class);

        // Add middleware to global middleware stack
        $this->app['router']->pushMiddlewareToGroup('web', BypassCsrfForPreview::class);
    }
}
