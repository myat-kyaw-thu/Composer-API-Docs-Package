<?php

namespace Primebeyonder\LaravelApiVisibility;

use Illuminate\Support\ServiceProvider;
use Primebeyonder\LaravelApiVisibility\Contracts\DocumentationGeneratorInterface;
use Primebeyonder\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use Primebeyonder\LaravelApiVisibility\Contracts\ResponsePreviewerInterface;
use Primebeyonder\LaravelApiVisibility\Core\DocumentationGenerator;
use Primebeyonder\LaravelApiVisibility\Core\RouteCollector;
use Primebeyonder\LaravelApiVisibility\Core\ResponsePreviewer;
use Primebeyonder\LaravelApiVisibility\Http\Middleware\BypassCsrfForPreview;
use Primebeyonder\LaravelApiVisibility\Support\ErrorHandler;

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
            __DIR__ . '/../config/api-visibility.php', 'api-visibility'
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
