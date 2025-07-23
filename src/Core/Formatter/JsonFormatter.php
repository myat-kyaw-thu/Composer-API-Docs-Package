<?php

namespace myatKyawThu\LaravelApiVisibility\Core\Formatter;

use Illuminate\Http\Response;
use myatKyawThu\LaravelApiVisibility\Contracts\FormatterInterface;

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

        // Check if content type contains application/json
        if ($contentType && strpos($contentType, 'application/json') !== false) {
            return true;
        }

        // If no content type or not JSON content type, check if content looks like JSON
        return $this->looksLikeJson($response->getContent());
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

        // Check if content starts with { or [ and ends with } or ]
        $startsWithBrace = str_starts_with($content, '{') && str_ends_with($content, '}');
        $startsWithBracket = str_starts_with($content, '[') && str_ends_with($content, ']');

        if ($startsWithBrace || $startsWithBracket) {
            // Try to decode it to make sure it's valid JSON
            json_decode($content);
            return json_last_error() === JSON_ERROR_NONE;
        }

        return false;
    }
}
