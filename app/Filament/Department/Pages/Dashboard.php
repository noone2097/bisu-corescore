<?php

namespace App\Filament\Department\Pages;

use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?string $title = 'Department Dashboard';

    public function getTitle(): string
    {
        return auth()->user()->department_name . ' Dashboard';
    }
}