<?php

use Illuminate\Support\Facades\Route;
use Primebeyonder\LaravelApiVisibility\Http\Controllers\DocsController;
use Primebeyonder\LaravelApiVisibility\Http\Middleware\EnsureDevEnvironment;

Route::group([
    'prefix' => config('api-visibility.docs_route', 'docs'),
    'middleware' => array_merge(
        [EnsureDevEnvironment::class],
        config('api-visibility.middleware', ['web'])
    )
], function () {
    Route::get('/', [DocsController::class, 'index'])->name('api-visibility.docs');
});
