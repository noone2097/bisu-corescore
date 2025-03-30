<?php

namespace App\Filament\OfficeAdmin\Resources\OfficeAccountsResource\Pages;

use App\Filament\OfficeAdmin\Resources\OfficeAccountsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HasBackUrl;

class CreateOfficeAccounts extends CreateRecord
{
    use HasBackUrl;

    protected static string $resource = OfficeAccountsResource::class;
}
