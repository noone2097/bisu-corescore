<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartmentAccounts extends ListRecords
{
    protected static string $resource = DepartmentAccountsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
