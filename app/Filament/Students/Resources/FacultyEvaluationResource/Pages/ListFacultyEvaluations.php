<?php

namespace App\Filament\Students\Resources\FacultyEvaluationResource\Pages;

use App\Filament\Students\Resources\FacultyEvaluationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFacultyEvaluations extends ListRecords
{
    protected static string $resource = FacultyEvaluationResource::class;

    protected static ?string $modelLabel = '';

    protected static ?string $title = "";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('')
                ->icon('heroicon-o-plus')
                ->size('lg'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return ' ';
    }
}
