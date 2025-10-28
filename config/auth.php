<?php

return [

    'defaults' => [
        'guard' => 'pembeli',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'pembeli' => [
            'driver' => 'session',
            'provider' => 'pembeli',
        ],

        'seniman' => [
            'driver' => 'session',
            'provider' => 'seniman',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'pembeli' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pembeli::class,
        ],

        'seniman' => [
            'driver' => 'eloquent',
            'model' => App\Models\Seniman::class,
        ],

        'admin' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];