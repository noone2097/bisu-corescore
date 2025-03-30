<?php

namespace App\Filament\ResearchAdmin\Resources\YearLevelResource\Pages;

use App\Filament\ResearchAdmin\Resources\YearLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HasBackUrl;

class CreateYearLevel extends CreateRecord
{
    use HasBackUrl;

    protected static string $resource = YearLevelResource::class;
}