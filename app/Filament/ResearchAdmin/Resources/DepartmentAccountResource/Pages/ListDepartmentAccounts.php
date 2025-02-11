<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentAccountResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartmentAccounts extends ListRecords
{
    protected static string $resource = DepartmentAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}