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

class OfficeAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('office-admin')
            ->path('office-admin')
            ->authGuard('admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Account Management')
                    ->icon('heroicon-s-building-office-2')
                    ->collapsible()
            ])
            ->collapsibleNavigationGroups(false)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/OfficeAdmin/Resources'), for: 'App\\Filament\\OfficeAdmin\\Resources')
            ->discoverPages(in: app_path('Filament/OfficeAdmin/Pages'), for: 'App\\Filament\\OfficeAdmin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/OfficeAdmin/Widgets'), for: 'App\\Filament\\OfficeAdmin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->resources([
                \App\Filament\OfficeAdmin\Resources\OfficeResource::class,
                \App\Filament\OfficeAdmin\Resources\AddOfficeResource::class,
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
