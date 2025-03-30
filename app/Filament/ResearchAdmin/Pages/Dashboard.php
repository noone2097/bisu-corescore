<?php

namespace App\Filament\ResearchAdmin\Pages;

use App\Filament\ResearchAdmin\Widgets\DepartmentRadarChart;
use App\Filament\ResearchAdmin\Widgets\EvaluationStatsWidget;
use App\Filament\ResearchAdmin\Widgets\TopDepartmentsChart;
use App\Filament\ResearchAdmin\Widgets\TopFacultyWidget;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'lg' => 3,
        ];
    }

    public function getWidgets(): array
    {
        return [
            EvaluationStatsWidget::class,
            TopDepartmentsChart::class,
            DepartmentRadarChart::class,
            TopFacultyWidget::class,
        ];
    }
}