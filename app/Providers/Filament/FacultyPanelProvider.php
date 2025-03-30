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

class FacultyPanelProvider extends PanelProvider
{
    use PreventBackNavigationTrait;

    public function panel(Panel $panel): Panel
    {
        return $this->configurePanel(
            $panel
                ->id('faculty')
                ->path('faculty')
                ->login()
                ->brandLogo(fn () => view('filament.components.logo'))
                ->globalSearch(false)
                ->colors([
                    'primary' => Color::Purple,
                ])
                ->discoverResources(in: app_path('Filament/Faculty/Resources'), for: 'App\\Filament\\Faculty\\Resources')
                ->discoverPages(in: app_path('Filament/Faculty/Pages'), for: 'App\\Filament\\Faculty\\Pages')
                ->pages([
                    Pages\Dashboard::class,
                    \App\Filament\Pages\Faculty\Profile::class,
                ])
                ->discoverWidgets(in: app_path('Filament/Faculty/Widgets'), for: 'App\\Filament\\Faculty\\Widgets')
                ->widgets([
                    \App\Filament\Faculty\Widgets\OverallPerformanceWidget::class,
                    \App\Filament\Faculty\Widgets\CriteriaPerformanceWidget::class,
                    // \App\Filament\Faculty\Widgets\DetailedCriteriaWidget::class,
                ])
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
                    \App\Http\Middleware\RedirectToProperPanelMiddleware::class,
                ])
                ->authMiddleware([
                    Authenticate::class,
                ])
                ->plugins(
                    [
                        FilamentApexChartsPlugin::make(),
                    ]
                )
        );
    }
}
