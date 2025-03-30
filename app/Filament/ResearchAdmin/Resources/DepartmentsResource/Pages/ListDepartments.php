<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentsResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
