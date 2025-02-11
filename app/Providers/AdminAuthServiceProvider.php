<?php

namespace App\Providers;

use App\Guards\AdminAccountGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AdminAuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Auth::extend('admin_account', function ($app, $name, array $config) {
            return new AdminAccountGuard(
                $name,
                Auth::createUserProvider($config['provider']),
                $app['session.store']
            );
        });
    }
}