<?php

namespace App\Exceptions;

use Exception;
use Filament\Notifications\Notification;

class InactiveAccountException extends Exception
{
    public function render()
    {
        Notification::make()
            ->danger()
            ->title('Inactive Account')
            ->body('Your account is currently inactive. Please contact the administrator.')
            ->persistent()
            ->send();

        return back();
    }
}