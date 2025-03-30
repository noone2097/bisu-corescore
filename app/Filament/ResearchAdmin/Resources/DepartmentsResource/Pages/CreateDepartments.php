<?php

namespace App\Filament\ResearchAdmin\Resources\DepartmentsResource\Pages;

use App\Filament\ResearchAdmin\Resources\DepartmentsResource;
use App\Traits\HasBackUrl;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartments extends CreateRecord
{
    use HasBackUrl;
    
    protected static string $resource = DepartmentsResource::class;
}
