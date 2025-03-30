<?php

namespace App\Filament\Calape\Pages\Auth;

use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;

class Login extends BaseLogin
{
    use WithRateLimiting;

    protected static string $view = 'filament.pages.auth.calape-login';
    
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        if (Auth::guard('web')->check()) {
            redirect()->intended(route('filament.calape.pages.dashboard'));
        }

        $this->form->fill();
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (\RuntimeException $exception) {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Too Many Attempts')
                ->body("You've made too many login attempts. Please wait {$exception->seconds} seconds before trying again.")
                ->send();
            return null;
        }

        $data = $this->form->getState();
        
        Log::info('Login attempt', ['data' => $data]);
        
        if (! Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], $data['remember'] ?? false)) {
            Log::info('Authentication failed');
            
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Sign in Failed')
                ->body('Please check your credentials and try again.')
                ->send();
            return null;
        }
        
        Log::info('Authentication successful', ['user_id' => Auth::id()]);
        
        session()->regenerate();
        
        \Filament\Notifications\Notification::make()
            ->title('Welcome back!')
            ->body('Logged in successfully!')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->seconds(5)
            ->send();
            
        return app(LoginResponse::class);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->prefixIcon('heroicon-m-envelope')
                    ->required()
                    ->autofocus()
                    ->helperText('Please use your BISU email'),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->prefixIcon('heroicon-m-key')
                    ->revealable()
                    ->required(),
                Checkbox::make('remember')
                    ->label('Remember me'),
            ]);
    }

    public function getTitle(): string
    {
        return '';
    }

    public function getHeading(): string
    {
        return '';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('authenticate')
                ->label('Sign in')
                ->submit('authenticate'),
        ];
    }

    public static function getAuthGuard(): string
    {
        return 'web';
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.calape.pages.dashboard');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    protected function getForgotPasswordUrl(): ?string
    {
        return null;
    }

    protected function getFooterActions(): array
    {
        return [
            Action::make('login_with_google')
                ->label('Sign in with Google')
                ->icon(fn () => view('icons.google'))
                ->color('gray')
                ->size('lg')
                ->button()
                ->url(route('filament.calape.auth.google'))
                ->extraAttributes([
                    'class' => 'w-full mt-2',
                    'style' => 'gap: 0.75rem;',
                ]),
                Action::make('request_password_reset')
                ->label('Forgot your password?')
                ->url(route('filament.calape.auth.password-reset.request'))
                ->color('gray')
                ->size('lg')
                ->extraAttributes([
                    'class' => 'w-full mt-2',
                    'style' => 'gap: 0.75rem;',
                ])
        ];
    }
}
