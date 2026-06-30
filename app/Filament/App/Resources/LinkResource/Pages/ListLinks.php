<?php

namespace App\Filament\App\Resources\LinkResource\Pages;

use App\Filament\App\Resources\LinkResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class ListLinks extends ListRecords
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Создать ссылку')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        
        // Логируем запрос
        Log::info('=== ListLinks getTableQuery ===', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);
        
        // Дамп данных из таблицы
        $data = $query->get();
        dump('Данные в таблице ListLinks:', $data->toArray());
        
        return $query;
    }
}