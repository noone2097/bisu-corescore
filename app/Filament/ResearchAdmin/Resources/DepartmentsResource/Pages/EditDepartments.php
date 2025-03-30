<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentsResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentsResource;
use App\Traits\HasBackUrl;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartments extends EditRecord
{
    use HasBackUrl;
    
    protected static string $resource = DepartmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
