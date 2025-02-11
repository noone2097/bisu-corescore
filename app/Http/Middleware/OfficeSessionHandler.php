<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Notifications\Notification;

class OfficeSessionHandler extends AuthenticateSession
{
    protected function shouldAuthenticateSession(): bool
    {
        return request()->is('office*');
    }

    public function handle($request, Closure $next)
    {
        if ($this->shouldAuthenticateSession()) {
            $office = auth('office')->user();
            
            if ($office && $office->status !== 'active') {
                auth('office')->logout();
                
                Notification::make()
                    ->danger()
                    ->title('Access Denied')
                    ->body('This office account is inactive. Please contact the administrator.')
                    ->send();
                
                return redirect()->route('filament.office.auth.login');
            }
        }

        return parent::handle($request, $next);
    }
}