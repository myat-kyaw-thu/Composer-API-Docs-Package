<?php

namespace myatKyawThu\LaravelApiVisibility\Core;

use myatKyawThu\LaravelApiVisibility\Contracts\DocumentationGeneratorInterface;
use myatKyawThu\LaravelApiVisibility\Contracts\RouteCollectorInterface;
use myatKyawThu\LaravelApiVisibility\Support\RouteGrouping;

class DocumentationGenerator implements DocumentationGeneratorInterface
{
    /**
     * @var RouteCollectorInterface
     */
    protected $routeCollector;

    /**
     * @var RouteGrouping
     */
    protected $routeGrouping;

    /**
     * Create a new documentation generator instance.
     *
     * @param RouteCollectorInterface $routeCollector
     * @param RouteGrouping $routeGrouping
     */
    public function __construct(RouteCollectorInterface $routeCollector, RouteGrouping $routeGrouping)
    {
        $this->routeCollector = $routeCollector;
        $this->routeGrouping = $routeGrouping;
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

        return $this->routeGrouping->group($routes, $groupBy);
    }
}
