<?php

namespace App\Http\Controllers;

use App\Models\Tapel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Helpers\SekolahHelper;
use App\Models\ArsipIjazah;

class ArsipController extends Controller
{
    public function homeRapor(Request $request)
    {
        try {
            $sekolahId = $request->user()->userable->sekolahs[0]->npsn;
            $tapels = Tapel::with([
                "rombels" => function ($q) use ($sekolahId) {
                    $q->where("rombels.sekolah_id", $sekolahId);
                    $q->whereHas("wali_kelas");
                    $q->with("siswas", "wali_kelas", "sekolah.ks");
                },
            ])->get();
            return Inertia::render("Dash/Arsip", [
                "tapels" => $tapels,
                "sekolahs" => \sekolahs($request->user()),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function homeIjazah(Request $request)
    {
        try {
            $sekolahId = $request->user()->userable->sekolahs[0]->npsn;
            return Inertia::render("Dash/Arsip/Ijazah", [
                "tapels" => Tapel::with([
                    'ijazahs' => function ($q) use ($sekolahId) {
                        $q->where('sekolah_id', $sekolahId);
                        $q->with('siswa');
                    },
                ])->get(),
            ]);
        }catch(\Exception $e)
        {

            dd($e);
        }
    }
}
