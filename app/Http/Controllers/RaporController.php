<?php

namespace App\Http\Controllers;

use App\Services\RaporService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RaporController extends Controller
{
    public function home(Request $request)
    {

        return Inertia::render('Dash/Rapor', []);
    }

    public function periodik(Request $request)
    {

        return Inertia::render('Dash/Periodik', []);
    }

    public function raporPTS(Request $request, RaporService $raporService)
    {
        $queries = $request->query();
        $nilaiPTS = $raporService->nilaiPTS($queries);
        // dd($nilaiPTS);
        return response()->json($nilaiPTS);
    }
}
