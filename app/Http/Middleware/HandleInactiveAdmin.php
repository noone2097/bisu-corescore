<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;

class HandleInactiveAdmin
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);

            return $response;
        } catch (\Exception $e) {
            // If it's an inactive account exception
            if (str_contains($e->getMessage(), 'inactive') || str_contains($e->getMessage(), 'deactivated')) {
                Notification::make()
                    ->danger()
                    ->title('Access Denied')
                    ->body($e->getMessage())
                    ->send();

                auth('admin')->logout();
                
                return redirect()->route('filament.admin.auth.login');
            }

            // Re-throw other exceptions
            throw $e;
        }
    }
}