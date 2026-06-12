<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sendbird Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for Sendbird Chat API.
    |
    */

    'app_id' => env('SENDBIRD_APP_ID'),
    'api_token' => env('SENDBIRD_API_TOKEN'),
    // The API URL should use the same case as the app_id
    'api_url' => env('SENDBIRD_API_URL'),
];
