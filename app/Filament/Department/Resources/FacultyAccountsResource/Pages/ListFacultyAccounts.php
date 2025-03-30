<?php

namespace App\Filament\Department\Resources\FacultyAccountsResource\Pages;

use App\Filament\Department\Resources\FacultyAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFacultyAccounts extends ListRecords
{
    protected static string $resource = FacultyAccountsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
