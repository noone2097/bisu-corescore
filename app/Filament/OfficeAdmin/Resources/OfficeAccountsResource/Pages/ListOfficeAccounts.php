<?php

namespace App\Filament\OfficeAdmin\Resources\OfficeAccountsResource\Pages;

use App\Filament\OfficeAdmin\Resources\OfficeAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfficeAccounts extends ListRecords
{
    protected static string $resource = OfficeAccountsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
