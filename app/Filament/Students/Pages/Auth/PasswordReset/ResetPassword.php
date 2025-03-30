<?php

namespace App\Filament\Students\Pages\Auth\PasswordReset;

use Filament\Pages\Auth\PasswordReset\ResetPassword as BaseResetPassword;
use Illuminate\Contracts\Support\Htmlable;

class ResetPassword extends BaseResetPassword
{
    protected static string $view = 'filament.pages.auth.password-reset.reset-password';

    public static function getAuthGuard(): string
    {
        return 'students';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Reset Password';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Set your new password';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getLoginUrl();
    }

    protected function getLoginUrl(): string
    {
        return route('filament.students.auth.login');
    }

    protected function getPasswordBroker(): string
    {
        return 'students';
    }
}
