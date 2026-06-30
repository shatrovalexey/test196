<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Зарегистрировать нового пользователя
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        if (auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Уже авторизованы',
            ], 403);
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Пользователь успешно зарегистрирован',
                'data' => new AuthResource([
                    'user' => $user,
                    'token' => $token,
                ]),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось выполнить регистрацию',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Войти в систему
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Уже авторизованы',
            ], 403);
        }
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Неверные учётные данные',
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Вход выполнен успешно',
                'data' => new AuthResource([
                    'user' => $user,
                    'token' => $token,
                ]),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось выполнить вход',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Выйти из системы
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Выход выполнен успешно',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось выйти из системы',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Получить данные аутентифицированного пользователя
     */
    public function me(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => new UserResource($request->user()),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось получить данные пользователя',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Обновить токен
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            // Удалить текущий токен
            $request->user()->currentAccessToken()->delete();
            
            // Создать новый токен
            $token = $request->user()->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Токен успешно обновлён',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось обновить токен',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}