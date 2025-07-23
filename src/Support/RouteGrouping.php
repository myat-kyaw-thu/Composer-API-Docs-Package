<?php

namespace myatKyawThu\LaravelApiVisibility\Support;

class RouteGrouping
{
    /**
     * Group routes by a specific field.
     *
     * @param array $routes
     * @param string $field
     * @return array
     */
    public function group(array $routes, string $field): array
    {
        $grouped = [];

        foreach ($routes as $route) {
            $key = $route[$field] ?? 'ungrouped';

            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }

            $grouped[$key][] = $route;
        }

        return $grouped;
    }

    /**
     * Group routes by prefix.
     *
     * @param array $routes
     * @return array
     */
    public function groupByPrefix(array $routes): array
    {
        return $this->group($routes, 'prefix');
    }

    /**
     * Group routes by namespace.
     *
     * @param array $routes
     * @return array
     */
    public function groupByNamespace(array $routes): array
    {
        return $this->group($routes, 'namespace');
    }

    /**
     * Group routes by middleware.
     *
     * @param array $routes
     * @return array
     */
    public function groupByMiddleware(array $routes): array
    {
        $grouped = [];

        foreach ($routes as $route) {
            $middleware = $route['middleware'] ?? [];

            foreach ($middleware as $m) {
                if (!isset($grouped[$m])) {
                    $grouped[$m] = [];
                }

                $grouped[$m][] = $route;
            }

            // If no middleware, add to 'none' group
            if (empty($middleware)) {
                if (!isset($grouped['none'])) {
                    $grouped['none'] = [];
                }

                $grouped['none'][] = $route;
            }
        }

        return $grouped;
    }
}
