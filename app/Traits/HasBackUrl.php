<?php

namespace App\Traits;

use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

trait HasBackUrl
{
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->url(fn () => $this->getResource()::getUrl('index'));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}