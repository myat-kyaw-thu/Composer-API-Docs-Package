<?php

namespace myatKyawThu\LaravelApiVisibility\Core\Formatter;

use Illuminate\Http\Response;
use myatKyawThu\LaravelApiVisibility\Contracts\FormatterInterface;

class MarkdownFormatter implements FormatterInterface
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

        // Convert markdown to HTML (you might want to use a proper Markdown parser)
        // For simplicity, we're just wrapping it in a pre tag
        return '<div class="markdown-content">' . $content . '</div>';
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

        return strpos($contentType, 'text/markdown') !== false ||
            $this->looksLikeMarkdown($response->getContent());
    }

    /**
     * Check if content looks like Markdown.
     *
     * @param string $content
     * @return bool
     */
    protected function looksLikeMarkdown(string $content): bool
    {
        // Simple heuristic: check for common Markdown patterns
        return preg_match('/^#+\s|\n#+\s|`{3}|\*{1,2}[^*]+\*{1,2}|\[.+\]$$.+$$/', $content) > 0;
    }
}
