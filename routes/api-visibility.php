<?php

use Illuminate\Support\Facades\Route;

Route::get(config('api-visibility.docs_route', '/docs'), function () {
    return view('api-visibility::docs');
})->name('api-visibility.docs');

Route::get(config('api-visibility.preview_route', '/preview'), function () {
    return view('api-visibility::preview');
})->name('api-visibility.preview');
