<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentAccountResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartmentAccount extends EditRecord
{
    protected static string $resource = DepartmentAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}