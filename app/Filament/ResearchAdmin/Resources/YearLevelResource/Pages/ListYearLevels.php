<?php

namespace App\Filament\ResearchAdmin\Resources\YearLevelResource\Pages;

use App\Filament\ResearchAdmin\Resources\YearLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListYearLevels extends ListRecords
{
    protected static string $resource = YearLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}