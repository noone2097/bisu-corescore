<?php

namespace App\Filament\OfficeAdmin\Resources\OfficeResource\Pages;

use App\Filament\OfficeAdmin\Resources\OfficeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOffice extends EditRecord
{
    protected static string $resource = OfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return OfficeResource::getUrl('index');
    }
}
