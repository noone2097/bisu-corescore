<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BasePage;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Office;

class Login extends BasePage
{
    public function authenticate(): LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $guards = ['office', 'office-admin', 'admin'];
        $data = $this->form->getState();

        // For office panel, check status before attempting login
        if (request()->is('office*')) {
            $office = Office::where('email', $data['email'])->first();
            if ($office && $office->status !== 'active') {
                Notification::make()
                    ->danger()
                    ->title('Access Denied')
                    ->body('This office account is inactive. Please contact the administrator.')
                    ->send();

                throw ValidationException::withMessages([
                    'data.email' => 'This office account is inactive.',
                ]);
            }
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->attempt([
                'email' => $data['email'],
                'password' => $data['password'],
            ])) {
                session()->regenerate();

                return app(LoginResponse::class);
            }
        }

        throw ValidationException::withMessages([
            'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}