<?php

namespace App\Providers\Filament;

use App\Providers\Filament\Traits\PreventBackNavigationTrait;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use App\Filament\Pages\OfficeAdmin\Profile;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Awcodes\LightSwitch\Enums\Alignment;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class OfficeAdminPanelProvider extends PanelProvider
{
    use PreventBackNavigationTrait;

    public function panel(Panel $panel): Panel
    {
        return $this->configurePanel(
            $panel
                ->id('office-admin')
                ->path('office-admin')
                ->login()
                ->brandLogo(fn () => view('filament.components.logo'))
                ->colors([
                    'primary' => Color::Purple,
                ])
                ->discoverResources(in: app_path('Filament/OfficeAdmin/Resources'), for: 'App\\Filament\\OfficeAdmin\\Resources')
                ->discoverPages(in: app_path('Filament/OfficeAdmin/Pages'), for: 'App\\Filament\\OfficeAdmin\\Pages')
                ->pages([
                    Pages\Dashboard::class,
                    Profile::class,
                ])
                ->discoverWidgets(in: app_path('Filament/OfficeAdmin/Widgets'), for: 'App\\Filament\\OfficeAdmin\\Widgets')
                ->widgets([
                ])
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
                    \App\Http\Middleware\RedirectToProperPanelMiddleware::class,
                ])
                ->authMiddleware([
                    Authenticate::class,
                ])
                ->plugins([
                    FilamentApexChartsPlugin::make()
                ])
        );
    }
}
