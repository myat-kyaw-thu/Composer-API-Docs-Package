<?php

namespace Hugom\LaravelApiVisibility\Providers;


use Illuminate\Support\ServiceProvider;

class ApiVisibilityServiceProvider extends ServiceProvider
{
   public function boot()
{
    $this->publishes([
        __DIR__.'/../../config/api-visibility.php' => config_path('api-visibility.php'),
    ], 'api-visibility-config');

    $this->loadRoutesFrom(__DIR__.'/../../routes/api-visibility.php');

    $this->loadViewsFrom(__DIR__.'/../../resources/views', 'api-visibility');
}

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/api-visibility.php', 'api-visibility');
    }

    /**
     * Resolve path to the config file (supports local package dev)
     */
    protected function resolveConfigPath(): string
    {
        return realpath(__DIR__ . '/../../config/api-visibility.php') ?: __DIR__ . '/../../config/api-visibility.php';
    }

    /**
     * Resolve path to the views directory (supports local package dev)
     */
    protected function resolveViewsPath(): string
    {
        return realpath(__DIR__ . '/../../resources/views') ?: __DIR__ . '/../../resources/views';
    }
}
