<?php

namespace myatKyawThu\LaravelApiVisibility\Core;

use myatKyawThu\LaravelApiVisibility\Contracts\DocumentationGeneratorInterface;
use myatKyawThu\LaravelApiVisibility\Contracts\RouteCollectorInterface;

class DocumentationGenerator implements DocumentationGeneratorInterface
{
    /**
     * @var RouteCollectorInterface
     */
    protected $routeCollector;

    /**
     * Create a new documentation generator instance.
     *
     * @param RouteCollectorInterface $routeCollector
     */
    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
    }

    /**
     * Generate API documentation.
     *
     * @return array
     */
    public function generate(): array
    {
        $routes = $this->routeCollector->collect();

        // Apply any additional processing or enrichment here

        return $routes;
    }

    /**
     * Group routes by a specific criteria.
     *
     * @param string $groupBy
     * @return array
     */
    public function groupBy(string $groupBy): array
    {
        $routes = $this->generate();

        return collect($routes)->groupBy($groupBy)->toArray();
    }
}
