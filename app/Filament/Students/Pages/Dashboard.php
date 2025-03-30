<?php

namespace App\Filament\Students\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.students.pages.dashboard';

    protected static ?int $navigationSort = -2;

    protected static ?string $navigationLabel = 'Dashboard';

    protected function getViewData(): array
    {
        return [
            'student' => auth()->guard('students')->user(),
        ];
    }

    public static function shouldRegister(): bool
    {
        return true;
    }

    public function getHeaderWidgets(): array
    {
        return [];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
    public function getHeading(): string
    {
        return ''; 
    }
}