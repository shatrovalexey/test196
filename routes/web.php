<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

// Публичный редирект (без авторизации)
Route::get('/l/{sref}', [RedirectController::class, 'redirect'])->name('redirect');