<?php

namespace App\Filament\Students\Resources\FacultyEvaluationResource\Pages;

use App\Filament\Students\Resources\FacultyEvaluationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\HasBackUrl;

class EditFacultyEvaluation extends EditRecord
{
    use HasBackUrl;
    protected static string $resource = FacultyEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
