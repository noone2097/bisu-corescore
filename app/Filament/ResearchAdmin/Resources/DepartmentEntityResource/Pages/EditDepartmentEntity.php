<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentEntityResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartmentEntity extends EditRecord
{
    protected static string $resource = DepartmentEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}