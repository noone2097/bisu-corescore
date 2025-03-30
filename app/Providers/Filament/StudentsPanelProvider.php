<?php

namespace App\Providers\Filament;

use App\Filament\Students\Pages\Auth\PasswordReset\RequestPasswordReset;
use App\Filament\Students\Pages\Dashboard;
use App\Models\Students;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Awcodes\LightSwitch\Enums\Alignment;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use App\Filament\Students\Pages\Auth\Login;
use App\Filament\Students\Pages\Auth\Register;

class StudentsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('students')
            ->path('students')
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset(
                RequestPasswordReset::class
            )
            ->emailVerification()
            ->authGuard('students')
            ->brandLogo(fn () => view('filament.components.logo'))
            ->colors([
                'primary' => Color::Blue,
            ])
            ->topNavigation()
            ->databaseNotifications()
            ->navigationGroups([
                'Evaluation Management',
                'My Account',
            ])
            ->breadcrumbs(false)
            ->discoverResources(in: app_path('Filament/Students/Resources'), for: 'App\\Filament\\Students\\Resources')
            ->discoverPages(in: app_path('Filament/Students/Pages'), for: 'App\\Filament\\Students\\Pages')
            ->pages([
                Dashboard::class
            ])
            ->discoverWidgets(in: app_path('Filament/Students/Widgets'), for: 'App\\Filament\\Students\\Widgets')
            ->widgets([])
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                LightSwitchPlugin::make()
                    ->position(Alignment::TopRight),
                FilamentSocialitePlugin::make()
                    ->providers([
                        Provider::make('google')
                            ->label('Sign in with Google')
                            ->icon('google'),
                    ])
            ]);
    }
}
