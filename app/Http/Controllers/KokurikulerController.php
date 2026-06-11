<?php

namespace App\Http\Controllers;

use App\Helpers\Periode;
use App\Helpers\RombelHelper;
use Illuminate\Http\Request;
use App\Models\Kokurikuler;
use App\Models\Rombel;
use Inertia\Inertia;

class KokurikulerController extends Controller
{
    public function home(Request $request)
    {
        // $koderombel = RombelHelper::data($request->user())[0]->kode;
        $rombels = Rombel::where('guru_id', $request->user()->userable->id)
                    ->where("tapel", Periode::tapel()->kode)
                    ->with([
                        'siswas' => function ($s) {
                            $s->with([
                                'kokurikulers' => function($k) {
                                    $k->where('tapel', Periode::tapel()->kode)
                                    ->where('semester', Periode::semester()->kode);
                                }
                            ]);
                        }
                        ])
                    ->get();
        return Inertia::render("Dash/Kokurikuler", 
    [
        'rombels' => $rombels,
    ]);
    }

    public function store(Request $request)
    {
        try {
            $store = Kokurikuler::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'siswa_id' => $request->siswa_id,
                    'tapel' => $request->tapel,
                    'semester' => $request->semester,
                    'rombel_id' => $request->rombel_id,
                    'deskripsi' => $request->deskripsi,
                    'guru_id' => $request->guru_id ?? $request->user()->userable->nip
                ]
            );
            return back()->with("message", "Nilai kokurikuler tersimpan");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
