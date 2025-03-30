<?php

namespace App\Filament\ResearchAdmin\Resources\AddDepartmentsResource\Pages;

use App\Filament\ResearchAdmin\Resources\AddDepartmentsResource;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = AddDepartmentsResource::class;

    protected function getRedirectUrl(): string
    {
        return \App\Filament\ResearchAdmin\Resources\DepartmentsResource::getUrl('index');
    }
}