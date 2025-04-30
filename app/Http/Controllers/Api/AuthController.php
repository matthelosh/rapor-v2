<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
// use JWTAuth as JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
// use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only("name", "password");
        if (!($token = JWTAuth::attempt($credentials))) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Username atau password tidak sesuai.",
                ],
                401
            );
        }

        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => \config("jwt.ttl") * 60,
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "role" => $role,
            ],
        ]);
    }
}
