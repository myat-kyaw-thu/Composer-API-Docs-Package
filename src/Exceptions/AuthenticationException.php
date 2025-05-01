<?php

namespace Primebeyonder\LaravelApiVisibility\Exceptions;

class AuthenticationException extends ApiVisibilityException
{
    /**
     * Create a new exception instance.
     *
     * @param string $routeName
     * @return void
     */
    public function __construct(string $routeName)
    {
        parent::__construct("Authentication required for route [{$routeName}].");

        $this->setSuggestions([
            "Log in to your application to access this protected route.",
            "If testing, you may need to simulate authentication.",
            "Check if the route has the 'auth' middleware applied.",
        ]);
    }
}
