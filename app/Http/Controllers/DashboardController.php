<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $sekolahs = [];
        if ($user->hasRole('admin')) {
            $sekolahs = Sekolah::all();
        } else {
            $sekolahs = $user->userable->sekolahs;
        }
        return Inertia::render('Dashboard', [
            'sekolahs' => $sekolahs,
        ]);
    }

    public function operator(Request $request)  {
        $user = $request->user();
        $ops = [];
        if ($user->hasRole('admin')) {
            $ops = Guru::whereHas('user.roles', function($q) {
                $q->where('name', 'ops');
            })->with('user.roles')->get();
        } else {
            $sekolah = Sekolah::where('npsn', $user->userable->sekolahs[0]->npsn)->first();
            $ops = Guru::whereHas('user.roles', function($q) {
                $q->where('name', 'ops');
            })->where('nip', $sekolah->npsn)->with('user.roles')->get();
        }
        
        return Inertia::render('Dash/Ops', [
            'ops' => $ops,
        ]);
    }
}
