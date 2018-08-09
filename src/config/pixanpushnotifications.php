<?php
/*
|--------------------------------------------------------------------------
| Laravel Pixan Push Notifications Config
|--------------------------------------------------------------------------
|
|
*/
return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Middleware Logger Options
    |--------------------------------------------------------------------------
    |
    | - log_requests      : true or false
    */
    'user_model' => '\App\User',

    'options' => [

        'NOTIFICATION_TYPE_VERIFY' => 'Pixan\PushNotifications\NOTIFICATION_TYPE_VERIFY',

        'environment'  => env('APP_ENV'),

        'local'	=> [
            'certificate' => '',
            'password'    => '',
            'url'         => 'ssl://gateway.sandbox.push.apple.com:2195',
        ],

        'production' => [
            'certificate' => '',
            'password'    => '',
            'url'         => 'ssl://gateway.push.apple.com:2195',
        ],
    ]
];
