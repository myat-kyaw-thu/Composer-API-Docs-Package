<?php

namespace Primebeyonder\LaravelApiVisibility\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureDevEnvironment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only allow access in non-production environments
        if (app()->environment('production') && !config('api-visibility.allow_in_production', false)) {
            abort(403, 'API Visibility is disabled in production.');
        }

        return $next($request);
    }
}
