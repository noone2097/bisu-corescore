<?php

namespace App\Filament\OfficeAdmin\Resources\AddOfficeResource\Pages;

use App\Filament\OfficeAdmin\Resources\AddOfficeResource;
use App\Filament\OfficeAdmin\Resources\OfficeResource;
use App\Notifications\OfficePasswordSetup;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class CreateOffice extends CreateRecord
{
    protected static string $resource = AddOfficeResource::class;

    protected function getRedirectUrl(): string
    {
        return OfficeResource::getUrl('index');
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
        return 'Add New Office Account';
    }

    public function getSubheading(): string
    {
        return 'Create a new office account with basic access.';
    }
}