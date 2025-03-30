<?php

namespace App\Filament\Department\Resources\FacultyAccountsResource\Pages;

use App\Filament\Department\Resources\FacultyAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HasBackUrl;
use Illuminate\Support\Facades\Auth;

class CreateFacultyAccounts extends CreateRecord
{
    use HasBackUrl;

    protected static string $resource = FacultyAccountsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['department_id'] = Auth::user()->department_id;
        
        return $data;
    }
}
