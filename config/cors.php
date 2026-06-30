<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Required so the static HTML/JS frontend (served from a different
    | origin/port than the Laravel API) is allowed to call this API.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // In production, replace '*' with your actual frontend origin(s).
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Must be false when allowed_origins is '*' (the frontend uses
    // Bearer tokens, not cookies, so credentials aren't needed).
    'supports_credentials' => false,

];
