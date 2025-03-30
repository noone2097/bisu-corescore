<?php

namespace App\Providers;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Livewire\Livewire;
use App\Filament\Calape\Pages\Auth\Login;

class AppServiceProvider extends ServiceProvider
{

    public $singletons = [
        \Filament\Http\Responses\Auth\Contracts\LoginResponse::class => \App\Http\Responses\LoginResponse::class,
        \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class => \App\Http\Responses\LogoutResponse::class, 
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('filament::print-layout', \Illuminate\View\Component::class);
        FilamentColor::register([
            'Purple' => Color::rgb('rgb(134,1,175)'),
        ]);

        Notifications::alignment(Alignment::Center);
        Notifications::verticalAlignment(VerticalAlignment::Start);

        // Register custom login component for Calape panel
        if (class_exists(Livewire::class)) {
            Livewire::component('filament.calape.pages.auth.login', Login::class);
        }

        // FilamentAsset::register([
        //     Js::make('custom-js', DIR . '/../../resources/js/custom.js'),
        // ]);
    }
}
