<?php
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

// Публичный редирект (без авторизации)
Route::get('/l/{sref}', [RedirectController::class, 'redirect'])->name('redirect');

// Редирект на панель пользователя
Route::get('/', function () {
    return redirect('/app');
});
Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/app/login');
})->name('logout');
// Маршруты для подтверждения email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/debug-session', function () {
    return [
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'auth_id' => auth()->id(),
        'auth_check' => auth()->check(),
        'session_table_count' => DB::table('sessions')->count(),
        'cookies' => request()->cookies->all(),
    ];
})->middleware('web');