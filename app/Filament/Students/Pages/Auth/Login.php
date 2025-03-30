<?php

namespace App\Filament\Students\Pages\Auth;

use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Actions\Action;
use App\Models\Students;
use Illuminate\Support\Facades\Log;

class Login extends BaseLogin
{
    use WithRateLimiting;

    protected static string $view = 'filament.pages.auth.login';
    
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        if (Auth::guard('students')->check()) {
            redirect()->intended(route('filament.students.pages.dashboard'));
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
        
        $login = $data['login'];
        $password = $data['password'];
        $remember = $data['remember'] ?? false;
        
        $student = Students::where('email', $login)
            ->orWhere('studentID', $login)
            ->first();
            
        if (!$student) {
            Log::info('No student found with identifier', ['login' => $login]);
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Sign in Failed')
                ->body('The student ID or email you entered was not found in our records. Please check your credentials and try again.')
                ->send();
            return null;
        }
        
        Log::info('Found student by identifier', [
            'student_id' => $student->id,
            'login_field' => $login
        ]);
        
        if (Auth::guard('students')->attempt([
            'id' => $student->id,
            'password' => $password,
        ], $remember)) {
            Log::info('Authentication successful using ID');
            session()->regenerate();
            
            \Filament\Notifications\Notification::make()
                ->title('Welcome back BISUAN!')
                ->body('Logged in successfully!')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->iconColor('success')
                ->seconds(5)
                ->send();
                
            return app(LoginResponse::class);
        }
        
        Log::info('Authentication failed for student', ['student_id' => $student->id]);
        \Filament\Notifications\Notification::make()
            ->danger()
            ->title('Authentication Failed')
            ->body('The password you entered is incorrect. Please check your password and try again.')
            ->send();
        return null;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('login')
                    ->label('Student ID or Email')
                    ->helperText('You can use your Student ID or BISU email')
                    ->prefixIcon('heroicon-m-user')
                    ->required()
                    ->autofocus(),
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
        return 'students';
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.students.pages.dashboard');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
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
                ->extraAttributes([
                    'class' => 'w-full mt-2',
                    'style' => 'gap: 0.75rem;',
                ])
                ->url('/students/oauth/google'),
            Action::make('request_password_reset')
                ->label('Forgot your password?')
                ->url(route('filament.students.auth.password-reset.request'))
                ->color('gray')
                ->size('lg')
                ->extraAttributes([
                    'class' => 'w-full mt-2',
                    'style' => 'gap: 0.75rem;',
                ])
        ];
    }
}