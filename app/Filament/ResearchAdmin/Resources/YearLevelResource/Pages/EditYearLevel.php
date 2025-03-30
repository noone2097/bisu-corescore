<?php

namespace App\Filament\ResearchAdmin\Resources\YearLevelResource\Pages;

use App\Filament\ResearchAdmin\Resources\YearLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\HasBackUrl;

class EditYearLevel extends EditRecord
{
    use HasBackUrl;

    protected static string $resource = YearLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}