<?php

namespace App\Filament\App\Widgets;

use App\Models\Link;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalLinks = Link::where('user_id', Auth::id())->count();
        $totalClicks = Link::where('user_id', Auth::id())
            ->withCount('logs')
            ->get()
            ->sum('logs_count');
        $todayClicks = Link::where('user_id', Auth::id())
            ->with(['logs' => function ($query) {
                $query->whereDate('created_at', today());
            }])
            ->get()
            ->sum(fn ($link) => $link->logs->count());

        return [
            Stat::make('Всего ссылок', $totalLinks)
                ->icon('heroicon-o-link')
                ->color('primary'),
            Stat::make('Всего переходов', $totalClicks)
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Stat::make('Переходов сегодня', $todayClicks)
                ->icon('heroicon-o-calendar')
                ->color('warning'),
        ];
    }
}