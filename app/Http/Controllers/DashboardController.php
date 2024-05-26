<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        if ($user->hasRole('admin')) {
            $sekolahs = Sekolah::all();
        }
        return Inertia::render('Dashboard', [
            'sekolahs' => $sekolahs,
        ]);
    }
}
