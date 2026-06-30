<?php

namespace App\Http\Middleware;

use App\Models\Link;
use Closure;
use Illuminate\Http\Request;

class CheckLinkOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $link = $request->route('link');
        
        // Проверяем, что ссылка существует и принадлежит текущему пользователю
        if (!$link || $link->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этой ссылке',
            ], 403);
        }

        return $next($request);
    }
}