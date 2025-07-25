<?php

namespace myatKyawThu\LaravelApiVisibility\Contracts;

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

    /**
     * Get all named routes.
     *
     * @return array
     */
    public function getNamedRoutes(): array;
}
