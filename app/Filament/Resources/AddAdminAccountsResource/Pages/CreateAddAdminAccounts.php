<?php

namespace App\Filament\Resources\AddAdminAccountsResource\Pages;

use App\Filament\Resources\AddAdminAccountsResource;
use App\Filament\Resources\AdminAccountsResource;
use App\Notifications\AdminPasswordSetup;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class CreateAddAdminAccounts extends CreateRecord
{
    protected static string $resource = AddAdminAccountsResource::class;

    protected function getRedirectUrl(): string
    {
        return AdminAccountsResource::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password_reset_token'] = Str::random(64);
        $data['password_reset_expires_at'] = now()->addHours(24);
        $data['password'] = bcrypt('temporary-' . Str::random(16));

        return $data;
    }

    public function getTitle(): string
    {
        return 'Add New Admin Account';
    }

    public function getSubheading(): string
    {
        return 'Create a new administrator account with basic access.';
    }
}
