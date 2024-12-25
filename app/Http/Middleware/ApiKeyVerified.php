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
        $clientId = $request->header('X-CLIENT-TOKEN');
        // $client = ApiClient::where('client_id', $clientId)->first();
        if ($clientId !== env('CLIENT_TOKEN')) {
            return response()->json(
                [
                    'error' => 'Unauthorized',
                    // 'token' => env('CLIENT_TOKEN'),
                    // 'x-header-token' => $clientId,
                    // 'same' => $clientId === env('CLIENT_TOKEN')
                ],
                401
            );
        }

        return $next($request);
    }
}
