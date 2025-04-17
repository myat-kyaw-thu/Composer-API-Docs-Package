<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Upload Driver
    |--------------------------------------------------------------------------
    */
    'default' => env('UPLOAD_SERVICE', 'local'),

    /*
    |--------------------------------------------------------------------------
    | DigitalOcean Spaces Settings
    |--------------------------------------------------------------------------
    */
    'digital_ocean' => [
        'key'      => env('DO_SPACES_KEY'),
        'secret'   => env('DO_SPACES_SECRET'),
        'endpoint' => env('DO_SPACES_ENDPOINT'),
        'region'   => env('DO_SPACES_REGION'),
        'bucket'   => env('DO_SPACES_BUCKET'),
    ],

];
