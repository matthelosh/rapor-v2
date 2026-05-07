<?php

namespace App\Services;

use App\Helpers\Periode;
use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiService
{
    /** @var \App\Models\User */
    // private $user = auth()->user();

    public function home($semester, $tapel)
    {
        $tapel ??= Periode::tapel()->kode;
        /** @var \App\Models\User */
        $user = Auth::user();
        /* $semester = Periode::semester()->kode; */
        $mapels = ["guru_agama", "guru_pjok", "guru_inggris"];
        if ($user->hasRole("guru_kelas")) {
            $nip = $user->userable->nip;

            $rombel = Rombel::whereHas("wali_kelas", function ($g) use ($nip) {
                $g->where("nip", $nip);
            })
                ->where('tapel', $tapel)
                ->with(["siswas" => function ($s) {
                    $s->orderBy("nama", "ASC");
                }])
                ->first();
            // dd($rombel);
            $sekolahId = $user->userable->sekolahs[0]->id;
            $datas = [
                "sekolah" => \sekolahs($user),
                "rombel" => $rombel,
                "mapels" => Mapel::where(
                    "fase",
                    "LIKE",
                    "%" . $rombel->fase . "%"
                )
                    ->whereHas("sekolah", function ($q) use ($sekolahId) {
                        $q->where("sekolahs.id", $sekolahId);
                    })
                    ->get(),
            ];
            // dd($datas);
        } elseif ($user->hasRole("guru_agama")) {
            $guruId = $user->userable->id;
            $agama = $user->userable->agama;
            $datas = Sekolah::whereHas("gurus", function ($q) use ($guruId) {
                $q->where("gurus.id", $guruId);
            })
                ->with("ks")
                ->with([
                    "rombels" => function ($q) use ($agama, $semester) {
                        $q->where('tapel', Periode::tapel()->kode);
                        $q->with('wali_kelas');
                        $q->with("siswas", function ($s) use ($agama) {
                            $s->where("siswas.agama", $agama)->orderBy(
                                "nama",
                                "ASC"
                            );
                        });
                        $q->with([
                            "nilais" => function ($n) use ($agama, $semester) {
                                $n->select(
                                    "rombel_id",
                                    DB::raw(
                                        "SUM(CASE WHEN tipe = 'uh' THEN 1 ELSE 0 END) as uh"
                                    ),
                                    DB::raw(
                                        "SUM(CASE WHEN tipe = 'ts' THEN 1 ELSE 0 END) as pts"
                                    ),
                                    DB::raw(
                                        "SUM(CASE WHEN tipe = 'as' THEN 1 ELSE 0 END) as pas"
                                    )
                                )
                                    ->where("agama", $agama)
                                    ->where("semester", $semester)
                                    ->groupBy("rombel_id");
                            },
                        ]);
                    },
                ])
                ->get();
            /**
             * datas => [
             *  rombels => [
             *      [
             *          'kode' => '1',
             *          'siswas' => [...]
             *          'nilais' => [
             *                  'uh' => '50%',
             *                  'pts' => '70%',
             *                  'pas' => '100%'
             *            ]
             *       ]
             *  ]
             * ]
             *
             *
             *
             */
        } elseif ($user->hasRole("ops")) {
            $datas = Sekolah::where("id", $user->userable->sekolahs[0]->id)
                ->with("rombels.siswas", "rombels.guru")
                ->first();
        } elseif ($user->hasRole("admin")) {
            $datas = Sekolah::all();
        } else {
            $guruId = $user->userable->id;
            $datas = Sekolah::whereHas("gurus", function ($q) use ($guruId, $semester) {
                $q->where("gurus.id", $guruId);
            })
                ->with("ks")
                ->with([
                    "rombels" => function ($r) use ($semester) {
                        $r->where('tapel', Periode::tapel()->kode)
                            ->with('siswas');
                        $r->with([
                            "nilais" => function ($n) use ($semester) {
                                $n->select(
                                    "rombel_id",
                                    DB::raw(
                                        "SUM(CASE WHEN tipe = 'uh' THEN 1 ELSE 0 END) as uh"
                                    ),
                                    DB::raw(
                                        "SUM(CASE WHEN tipe = 'ts' THEN 1 ELSE 0 END) as pts"
                                    ),
                                    DB::raw(
                                        "SUM(CASE WHEN tipe = 'as' THEN 1 ELSE 0 END) as pas"
                                    )
                                )
                                    ->where("semester", $semester)
                                    ->groupBy("rombel_id");
                            },
                        ]);
                    },
                ])
                ->get();
        }

        return $datas;
    }
}
