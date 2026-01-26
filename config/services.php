<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services.
    |
    */

    'mediawiki' => [
        'identifier' => env('MEDIAWIKI_IDENTIFIER'),
        'secret' => env('MEDIAWIKI_SECRET'),
        'callback_uri' => env('MEDIAWIKI_CALLBACK'),
        'base_url' => env('MEDIAWIKI_BASE'),
    ],
];
