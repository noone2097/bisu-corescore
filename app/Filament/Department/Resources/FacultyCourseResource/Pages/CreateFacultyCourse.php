<?php

namespace App\Filament\Department\Resources\FacultyCourseResource\Pages;

use App\Filament\Department\Resources\FacultyCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HasBackUrl;

class CreateFacultyCourse extends CreateRecord
{
    use HasBackUrl;
    protected static string $resource = FacultyCourseResource::class;
}
