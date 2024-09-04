<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SoalController extends Controller
{
    public function home(Request $request)
    {
        try {
            $user = $request->user();
            return Inertia::render(
                'Dash/Soal/Home',
                [
                    'canAddSoal' => $user->can('add_soal'),
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
