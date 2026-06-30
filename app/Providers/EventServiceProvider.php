<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\SetUserIdInSession;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            SetUserIdInSession::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}