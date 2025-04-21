<?php

namespace Primebeyonder\LaravelApiVisibility\Contracts;

interface RouteCollectorInterface
{
    /**
     * Collect all API routes.
     *
     * @return array
     */
    public function collect(): array;

    /**
     * Get a specific route by name.
     *
     * @param string $name
     * @return array|null
     */
    public function getRouteByName(string $name): ?array;
}
