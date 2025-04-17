<?php

namespace Primebeyonder\LaravelUploadService\Facades;

use Illuminate\Support\Facades\Facade;

class UploadFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \PrimeBeyonder\UploadService\Contracts\UploadInterface::class;
    }
}
