<?php

namespace App\Http\Controllers;

use App\Models\p5;
use Illuminate\Http\Request;
use Inertia\Inertia;

class P5Controller extends Controller
{
    public function home(Request $request)
    {
        try {
            return Inertia::render(
                'Dash/P5',
                [
                    "p5s" => p5::with('elemens.apds')->get(),
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
