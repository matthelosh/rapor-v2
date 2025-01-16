<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DaposyncController extends Controller
{
    public function syncSekolah(Request $request)
    {
        try {
            return response()->json(['success' => true, 'message' => 'Halo']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }

    }
}
