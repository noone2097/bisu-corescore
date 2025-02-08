<?php

namespace App\Filament\Resources\AdminAccountsResource\Pages;

use App\Filament\Resources\AdminAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminAccounts extends EditRecord
{
    protected static string $resource = AdminAccountsResource::class;

    protected function getRedirectUrl(): string
    {
        return AdminAccountsResource::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
