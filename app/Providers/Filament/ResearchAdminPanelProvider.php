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
use App\Filament\Pages\ResearchAdmin\Profile;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Awcodes\LightSwitch\Enums\Alignment;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class ResearchAdminPanelProvider extends PanelProvider
{
    use PreventBackNavigationTrait;

    public function panel(Panel $panel): Panel
    {
        return $this->configurePanel($panel
            ->id('research-admin')
            ->path('research-admin')
            ->login()
            ->brandLogo(fn () => view('filament.components.logo'))
            ->colors([
                'primary' => Color::Purple,
            ])
            ->discoverResources(in: app_path('Filament/ResearchAdmin/Resources'), for: 'App\\Filament\\ResearchAdmin\\Resources')
            ->discoverPages(in: app_path('Filament/ResearchAdmin/Pages'), for: 'App\\Filament\\ResearchAdmin\\Pages')
            ->pages([
                \App\Filament\ResearchAdmin\Pages\Dashboard::class,
                \App\Filament\Pages\ResearchAdmin\Profile::class,
            ])
            ->discoverWidgets(in: app_path('Filament/ResearchAdmin/Widgets'), for: 'App\\Filament\\ResearchAdmin\\Widgets')
            ->navigationGroups([
                NavigationGroup::make()
                ->label('Evaluation Management')
                ->icon('heroicon-o-clipboard-document-check'),
                NavigationGroup::make()
                    ->label('Department Accounts')
                    ->icon('mdi-account-tie'),
                NavigationGroup::make()
                    ->label('Department')
                    ->icon('heroicon-o-building-office-2'),
                NavigationGroup::make()
                    ->label('My Account')
                    ->icon('heroicon-o-user-circle'),
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
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
                \App\Http\Middleware\PreventBackHistory::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]))
            ->plugins([
                LightSwitchPlugin::make()
                    ->position(Alignment::TopCenter),
                FilamentApexChartsPlugin::make()
            ]);
    }
}
