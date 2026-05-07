<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $mapels = Mapel::with(['tps', 'elemens'])->get();

        return response()->json([
            'mapels' => $mapels,
        ]);
    }
}
