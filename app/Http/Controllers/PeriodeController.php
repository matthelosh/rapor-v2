<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tapel;
use App\Models\Semester;

class PeriodeController extends Controller
{
    public function home(Request $request)
    {
        try {
            return Inertia::render(
                'Dash/Periode',
                [
                    'tapels' => Tapel::all(),
                    'semesters' => Semester::all()
                ]
            );
        } catch(\Exception $e) {
            throw $e;
        }
    }
}
