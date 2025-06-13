<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Sekolah;
use App\Models\Siswa;

class BukuindukController extends Controller
{
    public function home(Request $request)
    {
        $ops = $request->user()->userable;
        $sekolah = Sekolah::whereHas("ops", function ($o) use ($ops) {
            $o->where("nip", $ops->nip);
        })->first();
        $siswas = Siswa::where("sekolah_id", $sekolah->npsn)->paginate(20);
        return Inertia::render("Dash/BukuInduk", [
            "siswas" => $siswas,
        ]);
    }
}
