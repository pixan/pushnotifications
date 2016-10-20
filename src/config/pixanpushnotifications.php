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
    'options' => [
        'environment'	=> env('APP_ENV'),

		'local'	=> [
			'certificate' 	=> '',
			'password' 		=> '',
			'url'			=> 'ssl://gateway.sandbox.push.apple.com:2195',
		],

		'production' => [
			'certificate' 	=> '',
			'password' 		=> '',
			'url'			=> 'ssl://gateway.push.apple.com:2195',
		],

    ]
];
