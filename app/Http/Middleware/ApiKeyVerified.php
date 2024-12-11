<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiClient;
use Illuminate\Support\Facades\Hash;

class ApiKeyVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientId = $request->header('X-CLIENT-ID');
        $client = ApiClient::where('client_id', $clientId)->first();
        $apiKey = $request->header('X-CLIENT-SECRET');
        if (!$client || ! Hash::check($apiKey, $client->secrete)) {
            return response()->json(['error' => 'Unauthorized'], 400);
        }

        return $next($request);
    }
}
