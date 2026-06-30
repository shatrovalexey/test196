<?php

namespace App\Filament\App\Resources\LinkResource\Pages;

use App\Filament\App\Resources\LinkResource;
use App\Models\Link;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLink extends CreateRecord
{
    protected static string $resource = LinkResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['sref'] = Link::generateUniqueSref();
        $data['created_at'] = now();
        
        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Ссылка успешно создана';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}