<?php

namespace Primebeyonder\LaravelApiVisibility\Contracts;

interface DocumentationGeneratorInterface
{
    /**
     * Generate API documentation.
     *
     * @return array
     */
    public function generate(): array;

    /**
     * Group routes by a specific criteria.
     *
     * @param string $groupBy
     * @return array
     */
    public function groupBy(string $groupBy): array;
}
