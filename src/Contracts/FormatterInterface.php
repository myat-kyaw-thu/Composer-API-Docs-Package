<?php

namespace Primebeyonder\LaravelApiVisibility\Contracts;

use Illuminate\Http\Response;

interface FormatterInterface
{
    /**
     * Format a response.
     *
     * @param Response $response
     * @return string
     */
    public function format(Response $response): string;

    /**
     * Check if this formatter can handle the response.
     *
     * @param Response $response
     * @return bool
     */
    public function canHandle(Response $response): bool;
}
