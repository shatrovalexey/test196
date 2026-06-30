<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\LinkResource\Pages;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;
    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationLabel = 'Мои ссылки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Создание ссылки')
                    ->schema([
                        Forms\Components\TextInput::make('href')
                            ->label('URL')
                            ->url()
                            ->required()
                            ->maxLength(2048)
                            ->placeholder('https://example.com')
                            ->helperText('Введите полный URL, включая https://'),
                        Forms\Components\TextInput::make('sref')
                            ->label('Короткий код')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Генерируется автоматически'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // ПРОВЕРКА ВСЕХ СПОСОБОВ ПОЛУЧИТЬ ПОЛЬЗОВАТЕЛЯ
                $user1 = auth()->user();
                $user2 = \Auth::user();
                $user3 = request()->user();
                $user4 = \Filament\Facades\Filament::auth()->user();
                $sessionUserId = session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                
                Log::info('=== modifyQueryUsing DEBUG ===');
                Log::info('auth()->user():', ['user' => $user1?->id]);
                Log::info('Auth::user():', ['user' => $user2?->id]);
                Log::info('request()->user():', ['user' => $user3?->id]);
                Log::info('Filament::auth()->user():', ['user' => $user4?->id]);
                Log::info('Session user_id:', ['user_id' => $sessionUserId]);
                Log::info('All session data:', session()->all());
                
                // ПРОВЕРКА ССЫЛОК
                $allLinks = Link::all();
                Log::info('Total links in DB:', ['count' => $allLinks->count()]);
                Log::info('All links:', $allLinks->toArray());
                
                // Если есть ссылки, проверяем их user_id
                if ($allLinks->count() > 0) {
                    $userIds = $allLinks->pluck('user_id')->unique()->toArray();
                    Log::info('User IDs in links:', $userIds);
                }
                
                // ВРЕМЕННО: показываем все ссылки
                Log::info('Showing ALL links');
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('href')
                    ->label('URL')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn ($state) => $state),
                Tables\Columns\TextColumn::make('sref')
                    ->label('Короткий код')
                    ->copyable()
                    ->copyMessage('Скопировано!')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('logs_count')
                    ->label('Переходы')
                    ->counts('logs')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Просмотр'),
                Tables\Actions\EditAction::make()
                    ->label('Редактировать'),
                Tables\Actions\DeleteAction::make()
                    ->label('Удалить'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Удалить выбранные'),
            ])
            ->emptyStateHeading('Нет ссылок')
            ->emptyStateDescription('Создайте свою первую короткую ссылку')
            ->emptyStateIcon('heroicon-o-link')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Создать ссылку')
                    ->url(static::getUrl('create'))
                    ->icon('heroicon-o-plus')
                    ->button(),
            ])
            ->paginated(false); // Временно отключаем пагинацию для отладки
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }
}