<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\Widget;
use Filament\Facades\Filament;

class UserInfoWidget extends Widget
{
    protected static string $view = 'filament.app.widgets.user-info-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public function getUser()
    {
        return Filament::auth()->user();
    }
}