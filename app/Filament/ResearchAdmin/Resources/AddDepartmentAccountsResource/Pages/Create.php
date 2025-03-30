<?php

namespace App\Filament\ResearchAdmin\Resources\AddDepartmentAccountsResource\Pages;

use App\Filament\ResearchAdmin\Resources\AddDepartmentAccountsResource;
use App\Traits\HasBackUrl;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
use Filament\Actions\Action;

class Create extends CreateRecord
{

    use HasBackUrl;
    protected static string $resource = AddDepartmentAccountsResource::class;

    protected function getRedirectUrl(): string
    {
        return \App\Filament\ResearchAdmin\Resources\DepartmentAccountsResource::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            Actions\CreateAction::make('create')
                ->label('Create')
                ->submit('create'),
        ];
    }
}