<?php

namespace App\Filament\OfficeAdmin\Resources\AddOfficeAccountsResource\Pages;

use App\Filament\OfficeAdmin\Resources\AddOfficeAccountsResource;
use App\Filament\OfficeAdmin\Resources\OfficeAccountsResource;
use App\Notifications\AccountSetupInvitation;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateOfficeAccounts extends CreateRecord
{
    protected static string $resource = AddOfficeAccountsResource::class;

    protected function getRedirectUrl(): string
    {
        return OfficeAccountsResource::getUrl('index');
    }
}