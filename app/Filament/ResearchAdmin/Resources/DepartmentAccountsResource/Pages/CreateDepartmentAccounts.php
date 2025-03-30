<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HasBackUrl;

class CreateDepartmentAccounts extends CreateRecord
{

    use HasBackUrl;

    protected static string $resource = DepartmentAccountsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
