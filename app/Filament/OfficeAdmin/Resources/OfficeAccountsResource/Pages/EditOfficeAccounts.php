<?php

namespace App\Filament\OfficeAdmin\Resources\OfficeAccountsResource\Pages;

use App\Filament\OfficeAdmin\Resources\OfficeAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\HasBackUrl;

class EditOfficeAccounts extends EditRecord
{

    use HasBackUrl;

    protected static string $resource = OfficeAccountsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
