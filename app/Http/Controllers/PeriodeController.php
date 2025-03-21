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
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function toggleTapel(Request $request, $id)
    {
        try {
            $tapel = Tapel::findOrFail($id);
            $not = Tapel::whereNot('id', $id);
            $not->update(['is_active' => 0]);
            $tapel->update(['is_active' => 1]);

            return back()->with('message', 'Tapel aktif diganti');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function toggleSemester(Request $request, $id)
    {
        try {
            $semester = Semester::findOrFail($id);
            $not = Semester::whereNot('id', $id);
            $not->update(['is_active' => 0]);
            $semester->update(['is_active' => 1]);

            return back()->with('message', 'Semester aktif diganti');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
