<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentAccountResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentAccountResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateDepartmentAccount extends CreateRecord
{
    protected static string $resource = DepartmentAccountResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $token = Str::random(64);
        
        return [
            ...$data,
            'status' => 'inactive',
            'password' => bcrypt('temporary-' . Str::random(16)),
            'password_reset_token' => $token,
            'password_reset_expires_at' => now()->addHours(24),
        ];
    }

    protected function afterCreate(): void
    {
        // Send password setup notification
        $this->record->notify(new \App\Notifications\DepartmentPasswordSetup($this->record->password_reset_token));
    }
}