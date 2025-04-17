<?php

namespace Primebeyonder\LaravelUploadService\Providers;

use Illuminate\Support\ServiceProvider;
use PrimeBeyonder\UploadService\Contracts\UploadInterface;
use PrimeBeyonder\UploadService\Services\LocalUploadService;
use Primebeyonder\LaravelUploadService\Services\DigitalOceanUploadService;
use InvalidArgumentException;

class UploadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/upload-service.php',
            'upload-service'
        );

        // Bind UploadInterface dynamically based on config
        $this->app->bind(UploadInterface::class, function ($app) {
            $driver = config('upload-service.default', 'local');

            return match ($driver) {
                'local' => new LocalUploadService(),
                'digital_ocean' => new DigitalOceanUploadService(),
                default => throw new InvalidArgumentException("Unsupported upload driver: {$driver}"),
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Publish config if running in console
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/upload-service.php' => config_path('upload-service.php'),
            ], 'upload-config');
        }
    }
}
