<?php

namespace App;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Tapel;
use App\Models\Tp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait NilaiTrait
{
    /**
     * array:8 [▼ // app/NilaiTrait.php:20
     *"siswas" => array:32 [▼
     * 0 => array:28 [
     *      "id" => 124
     *   "nisn" => "3173097628"
     *   "nis" => null
     *   "nik" => "3507214405170001"
     *   "nama" => "VANESA ARINDA MAYKA"
     *   "jk" => "Perempuan"
     *   "tempat_lahir" => "MALANG"
     *   "tanggal_lahir" => "2017-05-04"
     *   "alamat" => null
     *   "rt" => "-"
     *   "rw" => "-"
     *   "desa" => "-"
     *   "kecamatan" => "-"
     *   "kode_pos" => "-"
     *   "kabupaten" => "-"
     *   "Provinsi" => "-"
     *   "hp" => "085851945078"
     *   "email" => "3173097628@raporsd.net"
     *   "foto" => null
     *   "agama" => "Islam"
     *   "angkatan" => "2024"
     *   "sekolah_id" => "20518848"
     *   "status" => "aktif"
     *   "created_at" => "2025-01-22T01:30:17.000000Z"
     *   "updated_at" => "2025-03-20T02:14:31.000000Z"
     *   "dapo_id" => "faa9a5b4-8b95-4e0e-94d2-7cf936b80891"
     *   "pivot" => array:2 [▶]
     *   "nilai" => "90"
     * ],
     * ...
     *  ]
     *  "rombelId" => "20518848-2425-1A"
     *  "tingkat" => "1"
     *  "mapelId" => "pabp"
     *  "agama" => "Islam"
     *  "semester" => "1"
     *  "tapel" => "2425"
     *  "tipe" => "ts"
     *]
     */
    // public function simpanNilai($request)
    // {
    //     try {
    //         $siswas = $request->siswas;
    //         $query = $request->query();

    //         switch ($query["tipe"]) {
    //             case "ts":
    //             case "as":
    //                 foreach ($siswas as $siswa) {
    //                     $nilai = $siswa["nilai"] ?? 0;
    //                     $nilai = is_numeric($nilai) ? (float) $nilai : 0;
    //                     $nilai = max(0, min(100, $nilai));
    //                     $store = Nilai::updateOrCreate(
    //                         [
    //                             "tapel" => $query["tapel"],
    //                             "semester" => $query["semester"],
    //                             "siswa_id" => $siswa["nisn"],
    //                             "guru_id" => auth()->user()
    //                                 ? auth()->user()->userable->nip
    //                                 : $query["guruId"] ?? null,
    //                             "rombel_id" => $query["rombelId"],
    //                             "mapel_id" => $query["mapelId"],
    //                             "agama" => null,
    //                             "tp_id" => null,
    //                             "tipe" => $query["tipe"],
    //                         ],
    //                         [
    //                             "skor" => $nilai,
    //                         ]
    //                     );
    //                 }
    //                 return "Nilai " .
    //                     \strtoupper($query["tipe"]) .
    //                     " disimpan.";
    //                 break;
    //             case "uh":
    //                 $tpToSave = [];
    //                 foreach ($siswas as $siswa) {
    //                     foreach ($siswa["nilais"] as $k => $v) {
    //                         if ($k !== "ts" && $k !== "as") {
    //                             if (!isset($tpToSave[$k])) {
    //                                 $tpToSave[$k] = [];
    //                             }
    //                             $nilai = $siswa["nilai"] ?? 0;
    //                             $nilai = is_numeric($nilai)
    //                                 ? (float) $nilai
    //                                 : 0;
    //                             $nilai = max(0, min(100, $nilai));
    //                             $tpToSave[$k][] = $nilai;
    //                         }
    //                     }
    //                 }
    //                 $tpToSave = collect($tpToSave)
    //                     ->filter(function ($values) {
    //                         return collect($values)->sum() > 0;
    //                     })
    //                     ->toArray();

    //                 foreach ($siswas as $siswa) {
    //                     foreach ($siswa["nilais"] as $k => $v) {
    //                         if (
    //                             $k !== "ts" &&
    //                             $k !== "as" &&
    //                             isset($tpToSave[$k])
    //                         ) {
    //                             $tp = Tp::whereKode($k)->first();

    //                             $nilai = $siswa["nilai"] ?? 0;
    //                             $nilai = is_numeric($nilai)
    //                                 ? (float) $nilai
    //                                 : 0;
    //                             $nilai = max(0, min(100, $nilai));
    //                             if ($v !== null || $tp) {
    //                                 $store = Nilai::updateOrCreate(
    //                                     [
    //                                         "tapel" => $query["tapel"],
    //                                         "semester" => $query["semester"],
    //                                         "siswa_id" => $siswa["nisn"],
    //                                         "guru_id" => auth()->user()
    //                                             ->userable->nip,
    //                                         "rombel_id" => $query["rombelId"],
    //                                         "mapel_id" => $query["mapelId"],
    //                                         "agama" => $siswa["agama"] ?? null,
    //                                         "tp_id" => $k,
    //                                         "tipe" => "uh",
    //                                     ],
    //                                     [
    //                                         "skor" => $nilai,
    //                                     ]
    //                                 );
    //                             }
    //                         }
    //                     }
    //                 }
    //                 // return "Nilai Ulangan Harian Disimpan";
    //                 break;
    //             default:
    //                 foreach ($siswas as $siswa) {
    //                     foreach ($siswa["nilais"] as $k => $v) {
    //                         $tp = Tp::whereKode($k)->first();
    //                         $nilai = $siswa["nilai"] ?? 0;
    //                         $nilai = is_numeric($nilai) ? (float) $nilai : 0;
    //                         $nilai = max(0, min(100, $nilai));
    //                         if ($v !== null || $tp) {
    //                             $store = Nilai::updateOrCreate(
    //                                 [
    //                                     "tapel" => $query["tapel"],
    //                                     "semester" => $query["semester"],
    //                                     "siswa_id" => $siswa["nisn"],
    //                                     "guru_id" => auth()->user()->userable
    //                                         ->nip,
    //                                     "rombel_id" => $query["rombelId"],
    //                                     "mapel_id" => $query["mapelId"],
    //                                     "agama" => $siswa["agama"] ?? null,
    //                                     "tp_id" => \in_array($k, ["ts", "as"])
    //                                         ? null
    //                                         : $k,
    //                                     "tipe" => \in_array($k, ["ts", "as"])
    //                                         ? $k
    //                                         : "uh",
    //                                 ],
    //                                 [
    //                                     "skor" => $nilai,
    //                                 ]
    //                             );
    //                         }
    //                     }
    //                 }

    //                 return "Nilai Disimpan";
    //                 break;
    //         }

    //         Log::info("Completed simpanNilai successfully");
    //     } catch (\Throwable $th) {
    //         Log::error("Error in simpanNilai", [
    //             "error" => $th->getMessage(),
    //             "trace" => $th->getTraceAsString(),
    //         ]);
    //         throw $th;
    //     }
    // }
    public function simpanNilai($request)
    {
        try {
            $siswas = $request->siswas;
            $query = $request->query();

            switch ($query["tipe"]) {
                case "ts":
                case "as":
                    foreach ($siswas as $siswa) {
                        $store = Nilai::updateOrCreate(
                            [
                                "tapel" => $query["tapel"],
                                "semester" => $query["semester"],
                                "siswa_id" => $siswa["nisn"],
                                "guru_id" => auth()->user()
                                    ? auth()->user()->userable->nip
                                    : $query["guruId"] ?? null,
                                "rombel_id" => $query["rombelId"],
                                "mapel_id" => $query["mapelId"],
                                "agama" => $query["agama"] ?? null,
                                "tp_id" => null,
                                "tipe" => $query["tipe"],
                            ],
                            [
                                "skor" => $siswa["nilai"] ?? 0,
                            ]
                        );
                    }
                    return "Nilai " .
                        \strtoupper($query["tipe"]) .
                        " disimpan.";
                    break;
                case "uh":
                    foreach ($siswas as $siswa) {
                        foreach ($siswa["nilais"] as $k => $v) {
                            $tp = Tp::whereKode($k)->first();
                            if ($v !== null || $tp) {
                                $store = Nilai::updateOrCreate(
                                    [
                                        "tapel" => $query["tapel"],
                                        "semester" => $query["semester"],
                                        "siswa_id" => $siswa["nisn"],
                                        "guru_id" => auth()->user()->userable
                                            ->nip,
                                        "rombel_id" => $query["rombelId"],
                                        "mapel_id" => $query["mapelId"],
                                        "agama" => $query["agama"] ?? null,
                                        "tp_id" => $k,
                                        "tipe" => "uh",
                                    ],
                                    [
                                        "skor" => $v !== null ? $v : 0,
                                    ]
                                );
                            }
                        }
                    }
                    return "Nilai Ulangan Harian Disimpan";
                    break;
                default:
                    foreach ($siswas as $siswa) {
                        foreach ($siswa["nilais"] as $k => $v) {
                            $tp = Tp::whereKode($k)->first();
                            if ($v !== null || $tp) {
                                $store = Nilai::updateOrCreate(
                                    [
                                        "tapel" => $query["tapel"],
                                        "semester" => $query["semester"],
                                        "siswa_id" => $siswa["nisn"],
                                        "guru_id" => auth()->user()->userable
                                            ->nip,
                                        "rombel_id" => $query["rombelId"],
                                        "mapel_id" => $query["mapelId"],
                                        "agama" => $query["agama"] ?? null,
                                        "tp_id" => \in_array($k, ["ts", "as"])
                                            ? null
                                            : $k,
                                        "tipe" => \in_array($k, ["ts", "as"])
                                            ? $k
                                            : "uh",
                                    ],
                                    [
                                        "skor" => $v !== null ? $v : 0,
                                    ]
                                );
                            }
                        }
                    }

                    return "Nilai Disimpan";
                    break;
            }

            // Log::info('Completed simpanNilai successfully');
        } catch (\Throwable $th) {
            Log::error("Error in simpanNilai", [
                "error" => $th->getMessage(),
                "trace" => $th->getTraceAsString(),
            ]);
            throw $th;
        }
    }

    public function indexNilai($request)
    {
        try {
            $query = $request->query();
            $nilais = Nilai::where([
                ["tapel", "=", $query["tapel"]],
                ["semester", "=", $query["semester"]],
                ["rombel_id", "=", $query["rombelId"]],
                [
                    "agama",
                    "=",
                    $query["mapelId"] == "pabp" ? $query["agama"] : null,
                ],
                ["tipe", "=", $query["tipe"]],
                ["mapel_id", "=", $query["mapelId"]],
            ])->get();

            return $nilais;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function ledger($request)
    {
        $user = $request->user();
        $datas = [];
        $list1 = [];
        $list2 = [];

        if ($user->hasRole("guru_kelas")) {
            $tapel = $this->periode()["tapel"]["kode"];
            $semesterList = [1, 2];

            // Ambil rombel dan siswa
            $rombel = Rombel::where("guru_id", $user->userable->id)
                ->where("tapel", $tapel)
                ->with("siswas")
                ->first();

            $siswas = $rombel->siswas;
            $mapels = $this->mapels($user, $rombel);

            // Ambil semua nilai sekaligus
            $allNilai = Nilai::whereIn("siswa_id", $siswas->pluck("nisn"))
                ->where("rombel_id", $rombel->kode)
                ->where("tapel", $tapel)
                ->whereIn("semester", $semesterList)
                ->whereIn("mapel_id", $mapels->pluck("kode"))
                ->get()
                ->groupBy(
                    fn(
                        $n
                    ) => "{$n->siswa_id}-{$n->semester}-{$n->mapel_id}-{$n->tipe}"
                );

            foreach ($siswas as $siswa) {
                $data = [];
                $data["nisn"] = $siswa->nisn;
                $data["nama"] = $siswa->nama;
                $sum1 = 0;
                $sum2 = 0;

                foreach ($mapels as $mapel) {
                    $keyNas1 = "{$siswa->nisn}-1-{$mapel->kode}-as";
                    $keyUh1 = "{$siswa->nisn}-1-{$mapel->kode}-uh";
                    $keyNas2 = "{$siswa->nisn}-2-{$mapel->kode}-as";
                    $keyUh2 = "{$siswa->nisn}-2-{$mapel->kode}-uh";

                    $nas1 = $allNilai[$keyNas1][0] ?? null;
                    $nas2 = $allNilai[$keyNas2][0] ?? null;
                    $uh1 = $allNilai[$keyUh1] ?? collect();
                    $uh2 = $allNilai[$keyUh2] ?? collect();

                    $uh1 = $uh1->filter(fn($n) => $n->tp && $n->skor != 0);
                    $uh2 = $uh2->filter(fn($n) => $n->tp && $n->skor != 0);
                    $avgUh1 = ceil($uh1->avg("skor"));
                    $avgUh2 = ceil($uh2->avg("skor"));

                    $na1 = ceil(($avgUh1 + ($nas1?->skor ?? 0)) / 2);
                    $na2 = ceil(($avgUh2 + ($nas2?->skor ?? 0)) / 2);

                    $sum1 += $na1;
                    $sum2 += $na2;

                    $data[$mapel->kode] = [
                        "sem1" => $na1,
                        "sem2" => $na2,
                    ];
                }

                $data["sum1"] = $sum1;
                $data["sum2"] = $sum2;

                $list1[] = $sum1;
                $list2[] = $sum2;

                $datas[] = $data;
            }
        }

        return ["datas" => $datas, "lists" => [$list1, $list2]];
    }

    // {
    //     $user = $request->user();
    //     if ($user->hasRole("guru_kelas")) {
    //         $rombel = Rombel::where("guru_id", $request->user()->userable->id)
    //             ->whereTapel($this->periode()["tapel"]["kode"])
    //             ->with("siswas")
    //             ->first();
    //         $nilais = Nilai::where([
    //             ["tapel", "=", $this->periode()["tapel"]["kode"]],
    //             ["semester", "=", $this->periode()["tapel"]["semester"]],
    //             ["rombel_id", "=", $rombel->kode],
    //         ])
    //             ->get();

    //         $npsn = $request->user()->userable->sekolahs[0]->npsn;
    //         $datas = [];

    //         $mapels = $this->mapels($request->user(), $rombel);
    //         $list1 = [];
    //         $list2 = [];
    //         foreach ($rombel->siswas as $siswa) {
    //             $data = [];
    //             $data["nisn"] = $siswa->nisn;
    //             $data["nama"] = $siswa->nama;
    //             $sum1 = 0;
    //             $sum2 = 0;
    //             foreach ($mapels as $mapel) {
    //                 $nas1 = Nilai::where([
    //                     ["siswa_id", "=", $siswa->nisn],
    //                     ["rombel_id", "=", $rombel->kode],
    //                     ["tapel", "=", $this->periode()["tapel"]["kode"]],
    //                     ["semester", "=", "1"],
    //                     ["mapel_id", "=", $mapel->kode],
    //                     ["tipe", "=", "as"],
    //                 ])->first();

    //                 $avgUh1 = Nilai::whereHas("tp")
    //                     ->where([
    //                         ["siswa_id", "=", $siswa->nisn],
    //                         ["rombel_id", "=", $rombel->kode],
    //                         ["tapel", "=", $this->periode()["tapel"]["kode"]],
    //                         ["semester", "=", "1"],
    //                         ["mapel_id", "=", $mapel->kode],
    //                         ["tipe", "=", "uh"],
    //                     ])
    //                     ->avg("skor");

    //                 $na1 = ceil(
    //                     ($avgUh1 + ($nas1 !== null ? $nas1->skor : 0)) / 2
    //                 );
    //                 $nas2 = Nilai::where([
    //                     ["siswa_id", "=", $siswa->nisn],
    //                     ["rombel_id", "=", $rombel->kode],
    //                     ["tapel", "=", $this->periode()["tapel"]["kode"]],
    //                     ["semester", "=", "2"],
    //                     ["mapel_id", "=", $mapel->kode],
    //                     ["tipe", "=", "as"],
    //                 ])->first();

    //                 $avgUh2 = Nilai::whereHas("tp")
    //                     ->where([
    //                         ["siswa_id", "=", $siswa->nisn],
    //                         ["rombel_id", "=", $rombel->kode],
    //                         ["tapel", "=", $this->periode()["tapel"]["kode"]],
    //                         ["semester", "=", "2"],
    //                         ["mapel_id", "=", $mapel->kode],
    //                         ["tipe", "=", "uh"],
    //                     ])
    //                     ->avg("skor");

    //                 $na1 = ceil(
    //                     ($avgUh1 + ($nas1 !== null ? $nas1->skor : 0)) / 2
    //                 );
    //                 $na2 = ceil(
    //                     ($avgUh2 + ($nas2 !== null ? $nas2->skor : 0)) / 2
    //                 );
    //                 $sum1 += $na1;
    //                 $sum2 += $na2;
    //                 $data[$mapel->kode] = [
    //                     "sem1" => $na1,
    //                     "sem2" => $na2,
    //                 ];
    //             }
    //             $data["sum1"] = $sum1;
    //             $data["sum2"] = $sum2;
    //             \array_push($list1, $sum1);
    //             \array_push($list2, $sum2);
    //             array_push($datas, $data);
    //         }
    //     } else {
    //         $datas = [];
    //     }

    //     return ["datas" => $datas, "lists" => [$list1, $list2]];
    // }

    public function prosentase($user)
    {
        $userId = $user->id;
        $guruId = $user->userable->id;
        $tapel = $this->periode()["tapel"]->kode;
        try {
            // If Guru !== Guru Kelas dan MErangkap
            $res = [];
            if (!$user->hasRole("guru_kelas")) {
                $sekolahs = Sekolah::whereHas("gurus", function ($g) use (
                    $guruId
                ) {
                    $g->where("gurus.id", $guruId);
                })
                    ->with("rombels", function ($r) use ($tapel) {
                        $r->where("tapel", $tapel);
                        $r->with("siswas");
                    })
                    ->get();

                foreach ($sekolahs[0]->rombels as $rombel) {
                    \array_push($res, [
                        "rombel" => $rombel,
                        "nilais" => [
                            "pts" => Nilai::where("rombel_id", $rombel->kode)
                                ->where("tipe", "ts")
                                ->where("mapel_id", "pabp")
                                ->where(
                                    "semester",
                                    $this->periode()["semester"]->kode
                                )
                                ->count(),
                            "uhs" => Nilai::where("rombel_id", $rombel->kode)
                                ->where("tipe", "uh")
                                ->where("mapel_id", "pabp")
                                ->where(
                                    "semester",
                                    $this->periode()["semester"]->kode
                                )
                                ->count(),
                            "pas" => Nilai::where("rombel_id", $rombel->kode)
                                ->where("tipe", "as")
                                ->where("mapel_id", "pabp")
                                ->where(
                                    "semester",
                                    $this->periode()["semester"]->kode
                                )
                                ->count(),
                        ],
                    ]);
                }
            }
            // Else Guru KElas
            else {
            }

            return $res;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function mapels($user, $rombel)
    {
        $npsn = $user->userable->sekolahs[0]->npsn;
        return Mapel::whereHas("sekolah", function ($q) use ($npsn) {
            $q->where("npsn", $npsn);
        })
            ->where("fase", "LIKE", "%" . $rombel->fase . "%")
            ->get();
    }
    private function periode()
    {
        return [
            "semester" => Semester::whereIsActive(1)->first(),
            "tapel" => Tapel::whereisActive(1)->first(),
        ];
    }
}
