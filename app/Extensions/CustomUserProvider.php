<?php

namespace App\Extensions;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Notifications\Notification;

class CustomUserProvider extends EloquentUserProvider
{
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!$user->is_active) {
            $message = match($user->role) {
                'faculty' => 'Your account is currently inactive. Please check your email or contact your department administrator.',
                'department' => 'Your account is currently inactive. Please check your email or contact the research administrator.',
                'office' => 'Your account is currently inactive. Please check your email or contact the office administrator.',
                default => 'Your account is currently inactive. Please check your email or contact the administrator.'
            };

            Notification::make()
                ->danger()
                ->title('Inactive Account')
                ->body($message)
                ->persistent()
                ->send();

            return false;
        }

        return parent::validateCredentials($user, $credentials);
    }
}