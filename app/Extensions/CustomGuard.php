<?php

namespace App\Extensions;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InactiveAccountException;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class CustomGuard extends SessionGuard
{
    public function __construct($name, UserProvider $provider, Session $session, Request $request = null)
    {
        parent::__construct($name, $provider, $session, $request);
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($user && !$user->is_active) {
            Log::info('Login attempt for inactive user', ['user_id' => $user->id]);
            throw new InactiveAccountException('Your account is currently inactive. Please contact the administrator.');
        }

        return parent::attempt($credentials, $remember);
    }
}