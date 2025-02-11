<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentEntityResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartmentEntities extends ListRecords
{
    protected static string $resource = DepartmentEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}