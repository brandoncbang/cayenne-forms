<?php

return [

    'repo_url' => 'https://github.com/brandoncbang/cayenne-forms',

    /*
    |--------------------------------------------------------------------------
    | Demo
    |--------------------------------------------------------------------------
    |
    | Determines whether to enable the following features:
    |
    | - Seeder that generates a default account with forms & entries
    | - Scheduled task that wipes database and reseeds daily
    | - Page with a form pointing to an endpoint to let users test out Form
    |   Entries
    | - Redaction of sensitive Form Entry info
    |
    */

    'demo' => env('CAYENNE_DEMO', false),

];
