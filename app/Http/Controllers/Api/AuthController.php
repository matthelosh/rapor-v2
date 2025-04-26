<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Username atau password tidak sesuai.'], 401);
        }

        return response()->json([
            'access_token ' => $token,
            'token_type' => 'bearer',
            'expires_in' => \config('jwt.ttl') * 60,
            'user' => \auth('api')->user()->userable
        ]);
    }
}
