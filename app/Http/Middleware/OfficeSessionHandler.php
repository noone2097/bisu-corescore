<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\AuthenticateSession;

class OfficeSessionHandler extends AuthenticateSession
{
    protected function shouldAuthenticateSession(): bool
    {
        return request()->is('office*');
    }
}