<?php

namespace App\Filament\Department\Resources\CourseResource\Pages;

use App\Filament\Department\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HasBackUrl;
use Illuminate\Support\Facades\Auth;

class CreateCourse extends CreateRecord
{
use HasBackUrl;

protected static string $resource = CourseResource::class;

protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['department_id'] = Auth::user()->department_id;
    
    return $data;
}
}
