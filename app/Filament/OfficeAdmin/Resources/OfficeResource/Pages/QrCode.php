<?php

namespace App\Filament\OfficeAdmin\Resources\OfficeResource\Pages;

use App\Filament\OfficeAdmin\Resources\OfficeResource;
use Filament\Resources\Pages\Page;

class QrCode extends Page
{
    protected static string $resource = OfficeResource::class;

    protected static string $view = 'filament.office-admin.resources.office-resource.pages.qr-code';
}
