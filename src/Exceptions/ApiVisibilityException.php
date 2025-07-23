<?php

namespace myatKyawThu\LaravelApiVisibility\Exceptions;

use Exception;

class ApiVisibilityException extends Exception
{
    /**
     * Suggestions to fix the error.
     *
     * @var array
     */
    protected $suggestions = [];

    /**
     * Documentation link related to this error.
     *
     * @var string|null
     */
    protected $docsLink = null;

    /**
     * Get the suggestions to fix the error.
     *
     * @return array
     */
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    /**
     * Set suggestions to fix the error.
     *
     * @param array $suggestions
     * @return $this
     */
    public function setSuggestions(array $suggestions): self
    {
        $this->suggestions = $suggestions;
        return $this;
    }

    /**
     * Get the documentation link.
     *
     * @return string|null
     */
    public function getDocsLink(): ?string
    {
        return $this->docsLink;
    }

    /**
     * Set the documentation link.
     *
     * @param string $link
     * @return $this
     */
    public function setDocsLink(string $link): self
    {
        $this->docsLink = $link;
        return $this;
    }

    /**
     * Convert the exception to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'suggestions' => $this->getSuggestions(),
            'docs_link' => $this->getDocsLink(),
            'exception' => get_class($this),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => config('app.debug') ? $this->getTraceAsString() : null,
        ];
    }
}
