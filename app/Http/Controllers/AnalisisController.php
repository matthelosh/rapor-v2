<?php

namespace App\Http\Controllers;

use App\Helpers\Periode;
use App\Models\Asesmen;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalisisController extends Controller
{
    public function home(Request $request)
    {
        try {
            $asesmens = Asesmen::where('tapel', Periode::tapel()->kode)
                ->with('mapel')
                ->with('analises')
                ->get();
            return Inertia::render(
                'Dash/Analisis/Home',
                [
                    'asesmens' => $asesmens
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
