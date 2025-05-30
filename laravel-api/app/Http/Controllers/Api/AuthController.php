<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function generateToken(): JsonResponse
    {
        // Создание/поиск одного системного пользователя
        $user = User::firstOrCreate(
            ['email' => 'global@example.com'],
            [
                'name'     => 'Global User',
                'password' => bcrypt('global_password') // не используется
            ]
        );

        $token = $user->createToken('global-token')->plainTextToken;

        return response()->json([
            'token'      => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
