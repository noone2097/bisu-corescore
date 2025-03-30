<?php

namespace App\Filament\Department\Resources\CourseResource\Pages;

use App\Filament\Department\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\HasBackUrl;
use Illuminate\Support\Facades\Auth;

class EditCourse extends EditRecord
{
    use HasBackUrl;

    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['department_id'] = Auth::user()->department_id;
        
        return $data;
    }
}
