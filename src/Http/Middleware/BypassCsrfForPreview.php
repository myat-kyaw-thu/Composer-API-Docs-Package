<?php

namespace myatKyawThu\LaravelApiVisibility\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BypassCsrfForPreview
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
        // Check if this is a preview request
        $previewRoute = config('api-visibility.preview_route', 'preview');

        if ($request->is($previewRoute) || $request->is($previewRoute . '/*')) {
            // This is a preview request, so we'll bypass CSRF verification
            $request->attributes->set('api-visibility-preview', true);
        }

        return $next($request);
    }
}
