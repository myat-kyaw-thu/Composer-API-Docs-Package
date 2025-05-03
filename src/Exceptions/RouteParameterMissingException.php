<?php

namespace Primebeyonder\LaravelApiVisibility\Exceptions;

class RouteParameterMissingException extends ApiVisibilityException
{
    /**
     * The missing parameters.
     *
     * @var array
     */
    protected $missingParameters;

    /**
     * Create a new exception instance.
     *
     * @param string $routeName
     * @param array $missingParameters
     * @return void
     */
    public function __construct(string $routeName, array $missingParameters)
    {
        $this->missingParameters = $missingParameters;

        $missingParamsStr = implode(', ', $missingParameters);
        $message = "Missing required parameters for route [{$routeName}]: {$missingParamsStr}";

        parent::__construct($message);

        $this->setSuggestions([
            "Provide values for all required parameters: {$missingParamsStr}",
            "Check the route definition to see which parameters are required",
            "Use the form below to enter the required parameter values",
        ]);
    }

    /**
     * Get the missing parameters.
     *
     * @return array
     */
    public function getMissingParameters(): array
    {
        return $this->missingParameters;
    }

    /**
     * Convert the exception to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'missing_parameters' => $this->getMissingParameters(),
        ]);
    }
}
