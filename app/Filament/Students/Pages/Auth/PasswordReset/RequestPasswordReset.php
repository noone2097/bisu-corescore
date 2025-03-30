<?php

namespace App\Filament\Students\Pages\Auth\PasswordReset;

use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    protected static string $view = 'filament.pages.auth.password-reset.request-password-reset';

    protected function getCredentialsFromFormData(array $data): array
    {
        $email = $data['email'];
        if (!str_ends_with($email, '@bisu.edu.ph')) {
            $email .= '@bisu.edu.ph';
        }
        
        return [
            'email' => $email,
        ];
    }

    public static function getAuthGuard(): string
    {
        return 'students';
    }

    protected function getPasswordBroker(): string
    {
        return config('auth.defaults.passwords', 'students');
    }
}
