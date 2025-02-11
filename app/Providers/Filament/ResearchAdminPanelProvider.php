<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Forms\Components\TextInput;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ResearchAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('research-admin')
            ->path('research-admin')
            ->authGuard('admin')
            ->login()
            ->colors([
                'primary' => Color::Purple,
            ])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Research Administration')
                    ->icon('heroicon-s-academic-cap')
                    ->collapsible(),
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Department Management')
                    ->icon('heroicon-s-building-office-2')
                    ->collapsible(),
            ])
            ->collapsibleNavigationGroups(false)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/ResearchAdmin/Resources'), for: 'App\\Filament\\ResearchAdmin\\Resources')
            ->discoverPages(in: app_path('Filament/ResearchAdmin/Pages'), for: 'App\\Filament\\ResearchAdmin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/ResearchAdmin/Widgets'), for: 'App\\Filament\\ResearchAdmin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}