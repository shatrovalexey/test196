<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkStoreRequest;
use App\Http\Requests\LinkUpdateRequest;
use App\Http\Resources\LinkCollection;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Получить список всех ссылок пользователя
     */
    public function index(Request $request): LinkCollection
    {
        $links = Link::where('user_id', $request->user()->id)
            ->withCount('logs')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return new LinkCollection($links);
    }

    /**
     * Создать новую ссылку
     */
    public function store(LinkStoreRequest $request): JsonResponse
    {
        try {
            // Генерация уникального sref
            $sref = Link::generateUniqueSref();

            $link = Link::create([
                'user_id' => $request->user()->id,
                'href' => $request->href,
                'sref' => $sref,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ссылка успешно создана',
                'data' => new LinkResource($link),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при создании ссылки',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Получить информацию о конкретной ссылке
     */
    public function show(Request $request, Link $link): JsonResponse
    {
        // Проверка: ссылка принадлежит текущему пользователю
        if ($link->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этой ссылке',
            ], 403);
        }

        $link->loadCount('logs');

        return response()->json([
            'success' => true,
            'data' => new LinkResource($link),
        ]);
    }

    /**
     * Обновить ссылку (изменить URL)
     */
    public function update(LinkUpdateRequest $request, Link $link): JsonResponse
    {
        try {
            // Проверка: ссылка принадлежит текущему пользователю
            if ($link->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'У вас нет доступа к этой ссылке',
                ], 403);
            }

            $link->update([
                'href' => $request->href,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ссылка успешно обновлена',
                'data' => new LinkResource($link),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении ссылки',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Удалить ссылку
     */
    public function destroy(Request $request, Link $link): JsonResponse
    {
        try {
            // Проверка: ссылка принадлежит текущему пользователю
            if ($link->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'У вас нет доступа к этой ссылке',
                ], 403);
            }

            $link->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ссылка успешно удалена',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении ссылки',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Получить статистику по ссылкам пользователя
     */
    public function stats(Request $request): JsonResponse
    {
        $totalLinks = Link::where('user_id', $request->user()->id)->count();
        
        $totalClicks = Link::where('user_id', $request->user()->id)
            ->withCount('logs')
            ->get()
            ->sum('logs_count');

        $mostClicked = Link::where('user_id', $request->user()->id)
            ->withCount('logs')
            ->orderBy('logs_count', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'total_links' => $totalLinks,
                'total_clicks' => $totalClicks,
                'most_clicked' => $mostClicked ? new LinkResource($mostClicked) : null,
            ],
        ]);
    }
}