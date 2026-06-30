<?php

namespace App\Filament\App\Resources\LinkResource\Pages;

use App\Filament\App\Resources\LinkResource;
use Filament\Resources\Pages\EditRecord;

class EditLink extends EditRecord
{
    protected static string $resource = LinkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}