<?php

namespace App\Filament\OfficeAdmin\Pages;

use App\Filament\OfficeAdmin\Widgets\GlobalStatsWidget;
use App\Filament\OfficeAdmin\Widgets\ServiceQualityWidget;
use App\Filament\Widgets\CitizensCharterWidget;
use App\Filament\Widgets\VisitorClientTypeWidget;
use Filament\Pages\Dashboard as BasePage;
use Filament\Widgets;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getHeaderWidgets(): array
    {
        return [
            GlobalStatsWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 2,
            'lg' => 4,
            'xl' => 4,
        ];
    }

    protected function getHeaderWidgetsColumnSpan(): int | array | null
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 2,
            'lg' => 4,
            'xl' => 4,
        ];
    }

    public function getWidgets(): array
    {
        return [
            ServiceQualityWidget::class,
            CitizensCharterWidget::class,
            VisitorClientTypeWidget::class,
            Widgets\AccountWidget::class,
        ];
    }
}
