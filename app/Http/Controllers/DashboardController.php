<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $data = [];
        if ($user->hasRole('admin')) {
            $data['sekolahs'] = Sekolah::with('ks')->get();
            $data['tapels'] = Tapel::all();
            $data['semester'] = Semester::all();
        } else {
            $data['sekolahs'] = $user->userable->sekolahs;
        }
        return Inertia::render('Dashboard', [
            'data' => $data,
        ]);
    }

    public function operator(Request $request)
    {
        $user = $request->user();
        $ops = [];
        if ($user->hasRole('admin')) {
            $ops = Guru::whereHas('user.roles', function ($q) {
                $q->where('name', 'ops');
            })->with('user.roles')->get();
        } else {
            $sekolah = Sekolah::where('npsn', $user->userable->sekolahs[0]->npsn)->first();
            $ops = Guru::whereHas('user.roles', function ($q) {
                $q->where('name', 'ops');
            })->where('nip', $sekolah->npsn)->with('user.roles')->get();
        }

        return Inertia::render('Dash/Ops', [
            'ops' => $ops,
        ]);
    }
}
