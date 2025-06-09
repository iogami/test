<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Short Link Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the short link service.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Code Length
    |--------------------------------------------------------------------------
    |
    | The length of the generated short link codes.
    |
    */
    'code_length' => env('SHORTLINK_CODE_LENGTH', 6),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configuration for rate limiting on short link creation and access.
    |
    */
    'rate_limits' => [
        'creation' => [
            'max_attempts' => env('SHORTLINK_CREATION_MAX_ATTEMPTS', 120),
            'decay_minutes' => env('SHORTLINK_CREATION_DECAY_MINUTES', 1),
        ],
        'access' => [
            'max_attempts' => env('SHORTLINK_ACCESS_MAX_ATTEMPTS', 1000),
            'decay_minutes' => env('SHORTLINK_ACCESS_DECAY_MINUTES', 1),
        ],
    ],
]; 