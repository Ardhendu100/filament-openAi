<?php

use Illuminate\Support\Facades\Facade;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'open_ai_key' => env('OPENAI_API_KEY', ''),

    'aws_access_key' => env('AWS_ACCESS_KEY_ID',''),
    'aws_secret_access_key' => env('AWS_SECRET_ACCESS_KEY',''),
    'aws_default_region' => env('AWS_DEFAULT_REGION',''),
];
