<?php

namespace myatKyawThu\LaravelApiVisibility\Exceptions;

class RouteNotFoundException extends ApiVisibilityException
{
    /**
     * Create a new exception instance.
     *
     * @param string $routeName
     * @return void
     */
    public function __construct(string $routeName)
    {
        parent::__construct("Route [{$routeName}] not found.");

        $this->setSuggestions([
            "Check if the route name '{$routeName}' is correct.",
            "Ensure the route is registered in your routes file.",
            "Verify the route is not excluded in the api-visibility config.",
        ]);
    }
}
