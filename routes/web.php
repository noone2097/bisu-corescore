<?php

use App\Models\Departments;
use App\Models\FacultyEvaluation;
use App\Models\Students;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OfficeMetricsController;
use App\Http\Controllers\FacultyEvaluationPrintController;

Route::get('faculty-evaluations/print/{department}',
    [FacultyEvaluationPrintController::class, 'print'])
    ->name('faculty-evaluations.print');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/calape', function () {
    $prevUrl = url()->previous();
    $path = parse_url($prevUrl, PHP_URL_PATH);
    $panelId = explode('/', trim($path, '/'))[0];

    if (! in_array($panelId, array_keys(Filament::getPanels()))) {
        abort(404);
    }

    return redirect(route("filament.calape.auth.login"));
})
->middleware(['web', 'guest', 'prevent-back-history'])
->name('calape');

// Feedback Form Routes
Route::get('/feedback', [FeedbackController::class, 'showForm'])->name('feedback.form');
Route::get('/feedback/office/{office}', [FeedbackController::class, 'showForm'])->name('feedback.form.office');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::get('/feedback/thank-you/{office}', [FeedbackController::class, 'thankYou'])->name('thank-you');
Route::get('/feedback/qr-pdf/{qrCodePath}', [FeedbackController::class, 'generateQrPdf'])->name('feedback.qr.pdf')->where('qrCodePath', '.*');

// Office Metrics Print Route
Route::get('/office-metrics/print', [OfficeMetricsController::class, 'print'])
    ->name('office-metrics.print')
    ->middleware(['auth', 'verified']);


    Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
        $user = \App\Models\Students::findOrFail($id);
    
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
    
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('filament.students.pages.dashboard'));
        }
    
        $user->markEmailAsVerified();
    
        session()->flash('notification', [
            'message' => 'Email verified successfully!',
            'type' => 'success',
        ]);
    
        return redirect()->intended(route('filament.students.pages.dashboard'));
    })->middleware(['auth:students', 'signed'])->name('verification.verify');
    
    // Route for email verification notice page
    Route::get('/email/verify', function () {
        return redirect()->route('filament.students.auth.email-verification');
    })->middleware(['auth:students'])->name('verification.notice');
    
    // Route for resending verification email
    Route::post('/email/verification-notification', function () {
        $user = auth('students')->user();
        $user->sendEmailVerificationNotification();
        
        session()->flash('notification', [
            'message' => 'Verification link sent!',
            'type' => 'success',
        ]);
        
        return back();
    })->middleware(['auth:students', 'throttle:6,1'])->name('verification.send');
    
    // Google Login Routes for Students
    Route::get('/students/oauth/google', function () {
        return Socialite::driver('google')
            ->redirectUrl(env('GOOGLE_REDIRECT_URI'))
            ->redirect();
    })->name('filament.students.auth.google');
    
    Route::get('/students/oauth/google/callback', function (Illuminate\Http\Request $request) {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(env('GOOGLE_REDIRECT_URI'))
                ->user();
    
            \Log::info('Google OAuth login attempt', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);
    
            if (!str_ends_with($googleUser->getEmail(), '@bisu.edu.ph')) {
                Notification::make()
                    ->title('Invalid Email')
                    ->body('Please use your BISU email address.')
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-circle')
                    ->iconColor('danger')
                    ->seconds(5)
                    ->send();
                    
                return redirect()->route('filament.students.auth.login');
            }

            // Check if user exists with this email
            $student = Students::where('email', $googleUser->getEmail())->first();
            
            if (!$student) {
                // Store Google data in session for registration
                session([
                    'google_data' => [
                        'provider' => 'google',
                        'provider_id' => $googleUser->getId(),
                        'provider_token' => $googleUser->token,
                        'provider_refresh_token' => $googleUser->refreshToken,
                    ]
                ]);
                
                // Pre-fill some registration data
                session([
                    'registration_data' => [
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                    ]
                ]);
                
                Notification::make()
                    ->title('Registration Required')
                    ->body('Please complete your registration to continue.')
                    ->color('info')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('info')
                    ->seconds(5)
                    ->send();
                
                return redirect()->route('filament.students.auth.register');
            }
    
            // Update OAuth information for existing user
            $student->update([
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'provider_token' => $googleUser->token,
                'provider_refresh_token' => $googleUser->refreshToken,
            ]);
    
            // Mark email as verified for Google OAuth users
            if (!$student->hasVerifiedEmail()) {
                $student->markEmailAsVerified();
            }
    
            // Attempt to login the user
            Auth::guard('students')->logout(); // Clear any existing sessions first
            Auth::guard('students')->login($student, true); // true for "remember me"
            
            // Generate a new session
            $request->session()->regenerate();
            
            \Log::info('Student login successful', [
                'student_id' => $student->id,
                'auth_check' => Auth::guard('students')->check(),
                'session_id' => session()->getId(),
                'email_verified' => $student->hasVerifiedEmail()
            ]);
    
            // Double check authentication
            if (!Auth::guard('students')->check()) {
                throw new \Exception('Failed to authenticate student after login');
            }
    
            // Add success message and redirect
            Notification::make()
                ->title('Welcome back BISUan!')
                ->body('Logged in successfully!')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->iconColor('success')
                ->seconds(5)
                ->send();

            return redirect()->intended(route('filament.students.pages.dashboard'));
        } catch (\Exception $e) {
            Notification::make()
                ->title('Google Login Failed')
                ->body('Failed to login with Google: ' . $e->getMessage())
                ->color('danger')
                ->icon('heroicon-o-exclamation-circle')
                ->iconColor('danger')
                ->seconds(5)
                ->send();
                
            return redirect()->route('filament.students.auth.login');
        }
    })->name('filament.students.auth.google.callback');

// Google Login Routes for Calape
Route::get('/calape/oauth/google', function () {
    return Socialite::driver('google')
        ->redirectUrl(env('GOOGLE_CALAPE_REDIRECT_URI', env('GOOGLE_REDIRECT_URI')))
        ->redirect();
})->name('filament.calape.auth.google');

Route::get('/calape/oauth/google/callback', function (Illuminate\Http\Request $request) {
    try {
        $googleUser = Socialite::driver('google')
            ->redirectUrl(env('GOOGLE_CALAPE_REDIRECT_URI', env('GOOGLE_REDIRECT_URI')))
            ->user();

        \Log::info('Google OAuth login attempt for Calape', [
            'email' => $googleUser->getEmail(),
            'name' => $googleUser->getName()
        ]);

        if (!str_ends_with($googleUser->getEmail(), '@bisu.edu.ph')) {
            Notification::make()
                ->title('Invalid Email')
                ->body('Please use your BISU email address.')
                ->color('danger')
                ->icon('heroicon-o-exclamation-circle')
                ->iconColor('danger')
                ->seconds(5)
                ->send();
                
            return redirect('/calape');
        }

        // Check if user exists with this email
        $user = User::where('email', $googleUser->getEmail())->first();
        
        if (!$user) {
            Notification::make()
                ->title('Account Not Found')
                ->body('No account found with this email. Please contact your administrator.')
                ->color('danger')
                ->icon('heroicon-o-exclamation-circle')
                ->iconColor('danger')
                ->seconds(5)
                ->send();
                
            return redirect('/calape');
        }

        // Update OAuth information for existing user
        $user->update([
            'provider' => 'google',
            'provider_id' => $googleUser->getId(),
            'provider_token' => $googleUser->token,
            'provider_refresh_token' => $googleUser->refreshToken,
        ]);

        // Mark email as verified for Google OAuth users
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Attempt to login the user
        Auth::logout(); // Clear any existing sessions first
        Auth::login($user, true); // true for "remember me"
        
        // Generate a new session
        $request->session()->regenerate();
        
        \Log::info('User login successful', [
            'user_id' => $user->id,
            'auth_check' => Auth::check(),
            'session_id' => session()->getId(),
            'email_verified' => $user->hasVerifiedEmail()
        ]);

        // Add success message and redirect
        Notification::make()
            ->title('Welcome back!')
            ->body('Logged in successfully!')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->seconds(5)
            ->send();

        // Dynamically redirect based on user role
        return match($user->role) {
            'office-admin' => redirect('/office-admin'),
            'research-admin' => redirect('/research-admin'),
            'department' => redirect('/department'),
            'faculty' => redirect('/faculty'),
            'office' => redirect('/office'),
            default => redirect('/calape')
        };
        
    } catch (\Exception $e) {
        Notification::make()
            ->title('Google Login Failed')
            ->body('Failed to login with Google: ' . $e->getMessage())
            ->color('danger')
            ->icon('heroicon-o-exclamation-circle')
            ->iconColor('danger')
            ->seconds(5)
            ->send();
            
        return redirect('/calape');
    }
});