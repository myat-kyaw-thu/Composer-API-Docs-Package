<?php

namespace myatKyawThu\LaravelApiVisibility\Exceptions;

class InvalidResponseException extends ApiVisibilityException
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     * @param \Throwable|null $previous
     * @return void
     */
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->setSuggestions([
            "Check your controller's return value.",
            "Ensure your JSON is properly formatted.",
            "Verify that your response is properly serializable.",
            "Check for any database connection issues if fetching data.",
        ]);
    }
}
