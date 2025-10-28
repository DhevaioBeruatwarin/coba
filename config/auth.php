<?php

return [

    'defaults' => [
        'guard' => 'web', // default: pembeli
        'passwords' => 'pembelis',
    ],

    'guards' => [
        // guard untuk pembeli
        'web' => [
            'driver' => 'session',
            'provider' => 'pembelis',
        ],

        // guard untuk seniman
        'seniman' => [
            'driver' => 'session',
            'provider' => 'senimans',
        ],

        // guard untuk admin
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        // provider pembeli
        'pembelis' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pembeli::class,
        ],

        // provider seniman
        'senimans' => [
            'driver' => 'eloquent',
            'model' => App\Models\Seniman::class,
        ],

        // provider admin
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'pembelis' => [
            'provider' => 'pembelis',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'senimans' => [
            'provider' => 'senimans',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
