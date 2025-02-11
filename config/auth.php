<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin_accounts',
        ],
        'office' => [
            'driver' => 'session',
            'provider' => 'offices',
        ],
        'department' => [
            'driver' => 'session',
            'provider' => 'departments',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admin_accounts' => [
            'driver' => 'eloquent',
            'model' => App\Models\AdminAccounts::class,
        ],
        'offices' => [
            'driver' => 'eloquent',
            'model' => App\Models\Office::class,
        ],
        'departments' => [
            'driver' => 'eloquent',
            'model' => App\Models\Department::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admin_accounts' => [
            'provider' => 'admin_accounts',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'offices' => [
            'provider' => 'offices',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'departments' => [
            'provider' => 'departments',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
