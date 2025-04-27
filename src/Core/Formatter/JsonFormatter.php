<?php

namespace Primebeyonder\LaravelApiVisibility\Core\Formatter;

use Illuminate\Http\Response;
use Primebeyonder\LaravelApiVisibility\Contracts\FormatterInterface;

class JsonFormatter implements FormatterInterface
{
    /**
     * Format a response.
     *
     * @param Response $response
     * @return string
     */
    public function format(Response $response): string
    {
        $content = $response->getContent();

        // Try to decode and pretty print JSON
        $decoded = json_decode($content);

        if (json_last_error() === JSON_ERROR_NONE) {
            return json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        return $content;
    }

    /**
     * Check if this formatter can handle the response.
     *
     * @param Response $response
     * @return bool
     */
    public function canHandle(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type');

        return strpos($contentType, 'application/json') !== false ||
            $this->looksLikeJson($response->getContent());
    }

    /**
     * Check if content looks like JSON.
     *
     * @param string $content
     * @return bool
     */
    protected function looksLikeJson(string $content): bool
    {
        $content = trim($content);

        return (
            (str_starts_with($content, '{') && str_ends_with($content, '}')) ||
            (str_starts_with($content, '[') && str_ends_with($content, ']'))
        ) && json_decode($content) !== null;
    }
}
