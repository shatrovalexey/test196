<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class RedirectController extends Controller
{
    /**
     * Перенаправление по короткой ссылке
     * Доступно без авторизации
     */
    public function redirect(Request $request, string $sref): RedirectResponse|Response
    {
        try {
            // Находим ссылку по короткому идентификатору
            $link = Link::where('sref', $sref)->firstOrFail();

            // Логируем переход
            LinkLog::create([
                'link_id' => $link->id,
                'ip' => $request->ip(),
                'created_at' => now(),
            ]);

            // Перенаправляем на оригинальный URL
            return redirect()->away($link->href);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Ссылка не найдена - показываем страницу 404
            abort(404, 'Ссылка не найдена или удалена');
        }
    }
}