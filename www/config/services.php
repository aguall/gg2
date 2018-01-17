<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
	
	'facebook' => [
		'client_id' 	=> '1039160852821630',
		'client_secret' => 'fda1955dcf3631b5f2485fb2bc5758f2',
		'redirect' 		=> Config('app.url') . '/socialite/facebook/callback',
	],
	
	'vkontakte' => [
		'client_id' 	=> '5359578',
		'client_secret' => 'Rq5aRaa9uS6zcQesexqj',
		'redirect'		=> Config('app.url') . '/socialite/vkontakte/callback',
	], 
	
	'google' => [
		'client_id' 	=> '975776298846-210mmfhg5679dndot5kln9jbj15av8r8.apps.googleusercontent.com',
		'client_secret' => 'hypz6fCCorD6ELuwC1EEnC9A',
		'redirect' 		=> Config('app.url') . '/socialite/google/callback',
	],

];
