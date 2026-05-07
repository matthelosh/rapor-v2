<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ApiClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiClients = ApiClient::all();
        return Inertia::render('Dash/ApiClient', [
            'apiClients' => $apiClients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|string|unique:api_clients,client_id',
            'client_secret' => 'nullable|string|min:64|max:64'
        ]);

        // Generate client secret if not provided
        if (empty($validated['client_secret'])) {
            $validated['client_secret'] = Str::random(64);
        }

        $apiClient = ApiClient::create($validated);

        return back()->with('message', "API Client berhasil ditambahkan: $apiClient->client_id");
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiClient $apiClient)
    {
        return response()->json($apiClient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApiClient $apiClient)
    {
        $validated = $request->validate([
            'client_id' => 'required|string|unique:api_clients,client_id,' . $apiClient->id,
            'client_secret' => 'nullable|string|min:64|max:64'
        ]);

        // Generate new client secret if requested
        if ($request->has('regenerate_secret') && $request->regenerate_secret) {
            $validated['client_secret'] = Str::random(64);
        }

        $apiClient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'API Client berhasil diperbarui',
            'data' => $apiClient
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiClient $apiClient)
    {
        $apiClient->delete();

        return response()->json([
            'success' => true,
            'message' => 'API Client berhasil dihapus'
        ]);
    }

    /**
     * Regenerate client secret for the specified resource.
     */
    public function regenerateSecret(ApiClient $apiClient)
    {
        $apiClient->update([
            'client_secret' => Str::random(64)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Client Secret berhasil di-generate ulang',
            'data' => $apiClient
        ]);
    }
}
