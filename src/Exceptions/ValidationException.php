<?php

namespace myatKyawThu\LaravelApiVisibility\Exceptions;

class ValidationException extends ApiVisibilityException
{
    /**
     * The validation errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Create a new exception instance.
     *
     * @param array $errors
     * @return void
     */
    public function __construct(array $errors)
    {
        parent::__construct("The given data failed validation.");

        $this->errors = $errors;

        $this->setSuggestions([
            "Check the validation errors for specific issues.",
            "Ensure all required fields are provided.",
            "Verify the format of your input data matches the validation rules.",
        ]);
    }

    /**
     * Get the validation errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Convert the exception to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'errors' => $this->getErrors(),
        ]);
    }
}
