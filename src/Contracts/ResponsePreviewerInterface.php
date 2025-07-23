<?php

namespace myatKyawThu\LaravelApiVisibility\Contracts;

interface ResponsePreviewerInterface
{
    /**
     * Preview a response for a route.
     *
     * @param string $routeName
     * @param array $parameters
     * @return mixed
     */
    public function preview(string $routeName, array $parameters = []): mixed;
}
