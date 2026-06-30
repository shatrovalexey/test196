<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LinkController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    // Авторизация
    Route::prefix('user')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
        });
    });

    // Управление ссылками (только авторизованные)
    Route::prefix('links')
        ->middleware('auth:sanctum')
        ->group(function () {
            Route::get('/', [LinkController::class, 'index']);
            Route::post('/', [LinkController::class, 'store']);
            Route::get('/stats', [LinkController::class, 'stats']);
            
            // Маршруты с проверкой владельца
            Route::middleware('link.owner')->group(function () {
                Route::get('/{link}', [LinkController::class, 'show']);
                Route::put('/{link}', [LinkController::class, 'update']);
                Route::delete('/{link}', [LinkController::class, 'destroy']);
            });
        });
});