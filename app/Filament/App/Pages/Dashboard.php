<?php

namespace App\Filament\App\Pages;

use App\Models\Link;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Главная';
    protected static ?string $title = 'Личный кабинет';
    protected static ?string $slug = 'dashboard';

    protected static string $view = 'filament.app.pages.dashboard';

    public function getUser()
    {
        return Filament::auth()->user();
    }

    public function getTotalLinks(): int
    {
        return Link::where('user_id', Auth::id())->count();
    }

    public function getTotalClicks(): int
    {
        return Link::where('user_id', Auth::id())
            ->withCount('logs')
            ->get()
            ->sum('logs_count');
    }

    public function getRecentLinks()
    {
        return Link::where('user_id', Auth::id())
            ->withCount('logs')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getMostClickedLinks()
    {
        return Link::where('user_id', Auth::id())
            ->withCount('logs')
            ->orderBy('logs_count', 'desc')
            ->limit(5)
            ->get();
    }
}