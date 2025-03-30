<?php

namespace App\Filament\ResearchAdmin\Resources\FacultyEvaluationBinResource\Pages;

use App\Filament\ResearchAdmin\Resources\FacultyEvaluationBinResource;
use App\Filament\ResearchAdmin\Resources\FacultyEvaluationResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListFacultyEvaluationBin extends ListRecords
{
    protected static string $resource = FacultyEvaluationBinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back_to_evaluations')
                ->label('Back to Evaluations')
                ->url(fn () => FacultyEvaluationResource::getUrl())
                ->color('gray')
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
