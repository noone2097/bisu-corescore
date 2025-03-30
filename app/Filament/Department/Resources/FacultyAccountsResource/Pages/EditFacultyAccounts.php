<?php

namespace App\Filament\Department\Resources\FacultyAccountsResource\Pages;

use App\Filament\Department\Resources\FacultyAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\HasBackUrl;
use Illuminate\Support\Facades\Auth;

class EditFacultyAccounts extends EditRecord
{
    use HasBackUrl;

    protected static string $resource = FacultyAccountsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['department_id'] = Auth::user()->department_id;
        
        return $data;
    }
}
