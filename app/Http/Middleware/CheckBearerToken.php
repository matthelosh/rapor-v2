<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedToken = env("API_BEARER_TOKEN");

        if (!$expectedToken) {
            return response()->json(["error" => "Bearer token not found"], 500);
        }

        $authorizationHeader = $request->header("Authorization");

        if (
            !$authorizationHeader ||
            !str_starts_with($authorizationHeader, "Bearer ")
        ) {
            return response()->json(
                ["error" => "Invalid authorization header"],
                401,
            );
        }

        $token = substr($authorizationHeader, 7);

        if ($token !== $expectedToken) {
            return response()->json(["error" => "Invalid bearer token"], 401);
        }

        $request->attributes->set("bearer_token", $token);

        return $next($request);
    }
}
