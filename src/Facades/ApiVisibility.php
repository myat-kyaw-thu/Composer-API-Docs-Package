<?php

namespace Primebeyonder\LaravelApiVisibility\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getDocumentation()
 * @method static mixed previewResponse(string $routeName, array $parameters = [])
 *
 * @see \Primebeyonder\LaravelApiVisibility\FacadeManager
 */
class ApiVisibility extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api-visibility';
    }
}
