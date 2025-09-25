<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiClient;

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
        $clientSecret = $request->header('X-CLIENT-SECRET');

        if (!$clientId || !$clientSecret) {
            return response()->json([
                'error' => 'Missing client credentials',
                'message' => 'X-CLIENT-ID and X-CLIENT-SECRET headers are required'
            ], 401);
        }

        $client = ApiClient::where('client_id', $clientId)
                          ->where('client_secret', $clientSecret)
                          ->first();

        if (!$client) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid client credentials'
            ], 401);
        }

        // Tambahkan client ke request untuk digunakan di controller jika needed
        $request->merge(['api_client' => $client]);

        return $next($request);
    }
}
