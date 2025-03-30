<?php

namespace App\Filament\Students\Pages\Auth;

use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Livewire\Features\SupportRedirects\Redirector;

class EmailVerification extends SimplePage
{
    use WithRateLimiting;

    protected static string $view = 'filament.pages.auth.email-verification';

    protected static string $guard = 'students';

    protected static bool $shouldRegisterNavigation = false;

    public function mount(): void
    {
        if (! auth(static::$guard)->check()) {
            $this->redirectToLogin();
            return;
        }

        $user = auth(static::$guard)->user();

        if (! $user instanceof MustVerifyEmail) {
            $this->redirectToDashboard();
            return;
        }

        if ($user->hasVerifiedEmail()) {
            $this->redirectToDashboard();
            return;
        }
    }

    public function resendNotification(): void
    {
        $user = auth(static::$guard)->user();

        if (! $user instanceof MustVerifyEmail) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            $this->redirectToDashboard();
            return;
        }

        try {
            $this->rateLimit(2);
        } catch (\RuntimeException $exception) {
            Notification::make()
                ->title('Please wait before requesting another verification email')
                ->danger()
                ->send();
            return;
        }

        $user->sendEmailVerificationNotification();

        Notification::make()
            ->title('Verification email sent successfully')
            ->success()
            ->send();
    }

    protected function redirectToLogin(): Redirector
    {
        return redirect()->to(route('filament.students.auth.login'));
    }

    protected function redirectToDashboard(): Redirector
    {
        return redirect()->to(route('filament.students.pages.dashboard'));
    }

    public function getTitle(): string
    {
        return 'Verify Your Email';
    }

    public function getHeading(): string
    {
        return '';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('resend')
                ->label('Resend Verification Email')
                ->action('resendNotification'),
        ];
    }
}