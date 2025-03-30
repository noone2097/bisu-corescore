<?php

namespace App\Filament\OfficeAdmin\Resources\FeedbackBinResource\Pages;

use App\Filament\OfficeAdmin\Resources\FeedbackBinResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedbackBins extends ListRecords
{
    protected static string $resource = FeedbackBinResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
