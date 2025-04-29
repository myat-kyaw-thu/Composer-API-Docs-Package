<?php

use Illuminate\Support\Facades\Route;
use Primebeyonder\LaravelApiVisibility\Http\Controllers\PreviewController;
use Primebeyonder\LaravelApiVisibility\Http\Middleware\EnsureDevEnvironment;

Route::group([
    'prefix' => config('api-visibility.preview_route', 'preview'),
    'middleware' => array_merge(
        [EnsureDevEnvironment::class, 'api-visibility-bypass-csrf'],
        config('api-visibility.middleware', ['web'])
    )
], function () {
    Route::get('/', [PreviewController::class, 'index'])->name('api-visibility.preview');
    Route::get('/{routeName}', [PreviewController::class, 'show'])->name('api-visibility.preview.show');
});
