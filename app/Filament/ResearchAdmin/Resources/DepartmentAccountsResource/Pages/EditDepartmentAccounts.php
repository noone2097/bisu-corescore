<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Actions\ForceDeleteAction;
use App\Traits\HasBackUrl;

class EditDepartmentAccounts extends EditRecord
{
    use HasBackUrl;

    protected static string $resource = DepartmentAccountsResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
