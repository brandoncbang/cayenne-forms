<?php

return [

    'repo_url' => 'https://github.com/brandoncbang/cayenne-forms',

    /*
    |--------------------------------------------------------------------------
    | Remove Sensitive Info
    |--------------------------------------------------------------------------
    |
    | Determines whether a client's IP address & user agent will be redacted
    | when receiving a form entry.
    |
    */

    'remove_sensitive_info' => env('CAYENNE_REMOVE_SENSITIVE_INFO', false),

];
