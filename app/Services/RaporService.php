<?php

namespace App\Services;

use App\Helpers\Periode;
use App\Models\Absensi;
use App\Models\Catatan;
use App\Models\Guru;
use App\Models\Kktp;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\NilaiEkskul;
use App\Models\Rapor;
use App\Models\RaporDetail;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Tapel;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TanggalRapor;
use Illuminate\Support\Facades\Auth;

class RaporService
{
    public function nilaiPTS($queries)
    {
        try {
            $sekolahId = $queries["sekolahId"];
            $fase = Rombel::where("kode", $queries["rombelId"])->value("fase");
            $sekolah = Sekolah::whereNpsn($sekolahId)
                ->with([
                    "ekskuls",
                    "mapels" => function ($m) use ($fase) {
                        $m->where("mapels.fase", "LIKE", "%" . $fase . "%");
                        $m->orderBy("id", "ASC");
                    },
                    // fn($m, $fase) => $m->where('fase', $fase)->orderBy('id', 'ASC'),
                ])
                ->first();
            $mapels = $sekolah->mapels;
            $siswa = Siswa::whereNisn($queries["siswaId"])->first();
            $nilais = ["pts" => []];
            foreach ($mapels as $mapel) {
                $npts = Nilai::where([
                    ["siswa_id", "=", $queries["siswaId"]],
                    ["rombel_id", "=", $queries["rombelId"]],
                    ["tapel", "=", $queries["tapel"]],
                    ["semester", "=", $queries["semester"]],
                    ["mapel_id", "=", $mapel["kode"]],
                    ["tipe", "=", "ts"],
                ])
                    ->with("mapel")
                    ->first();

                \array_push(
                    $nilais["pts"],
                    $npts ?? [
                        "tapel" => Periode::tapel(),
                        "semester" => $queries["semester"]
                            ? Semester::whereKode($queries["semester"])->first()
                            : Periode::semester(),
                        "siswa_id" => $queries["siswaId"],
                        "guru_id" => Auth::user()->userable->nip,
                        "rombel_id" => $queries["rombelId"],
                        "mapel_id" => $mapel->kode,
                        "agama" =>
                            $mapel->kode == "pabp" ? $siswa->agama : null,
                        "tipe" => "ts",
                        "skor" => 0,
                        "mapel" => $mapel,
                    ]
                );
            }

            // $nilais = [
            //     'pts' => Nilai::where([
            //         ['siswa_id', '=', $queries['siswaId']],
            //         ['rombel_id', '=', $queries['rombelId']],
            //         ['semester', '=', $queries['semester']],
            //         ['tipe', '=', 'ts']
            //     ])->select('nilais.*')
            //         ->with('mapel')
            //         ->join('mapels', 'mapels.kode', '=', 'nilais.mapel_id')
            //         ->orderBy('mapels.id')
            //         ->get(),
            // ];
            $nilais["tapel"] = $queries["tapel"]
                ? Tapel::where("kode", $queries["tapel"])->first()
                : Periode::tapel();
            $nilais["semester"] = $queries["semester"]
                ? Semester::where("kode", $queries["semester"])->first()
                : Periode::semester();
            $tgl = TanggalRapor::where([
                ["semester", "=", $queries["semester"]],
                ["tipe", "=", "pts"],
                ["tapel", "=", $queries["tapel"]],
            ])
                ->select("tanggal")
                ->first();
            $nilais["tanggal"] = $tgl ? $tgl->tanggal : date("Y-M-d");
            return $nilais;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function nilaiPAS($queries)
    {
        try {
            $rombel = Rombel::where("kode", $queries["rombelId"])->first();
            $fase = $rombel->fase;
            $sekolah = Sekolah::where("npsn", $queries["sekolahId"])
                ->with("mapels", function ($q) use ($fase) {
                    $q->where("fase", "LIKE", "%" . $fase . "%");
                })
                ->first();
            $npsn = $sekolah->npsn;
            $mapels = Mapel::whereHas("sekolah", function ($q) use ($npsn) {
                $q->where("npsn", $npsn);
            })
                ->where("fase", "LIKE", "%" . $rombel->fase . "%")
                ->orderBy("id", "ASC")
                ->get();
            $nilais = [];
            $nomor = 0;
            foreach ($mapels as $mapel) {
                $nomor++;
                $kktp = Kktp::where("rombel_id", $rombel->kode)
                    ->where("mapel_id", $mapel->kode)
                    ->where("sekolah_id", $sekolah->npsn)
                    ->where("tapel", $queries["tapel"])
                    ->where("semester", $queries["semester"])
                    ->first();

                $nas = Nilai::where([
                    ["siswa_id", "=", $queries["siswaId"]],
                    ["rombel_id", "=", $queries["rombelId"]],
                    ["tapel", "=", $queries["tapel"]],
                    ["semester", "=", $queries["semester"]],
                    ["mapel_id", "=", $mapel["kode"]],
                    ["tipe", "=", "as"],
                ])->first();

                $nuhs = Nilai::where([
                    ["siswa_id", "=", $queries["siswaId"]],
                    ["rombel_id", "=", $queries["rombelId"]],
                    ["tapel", "=", $queries["tapel"]],
                    ["semester", "=", $queries["semester"]],
                    ["mapel_id", "=", $mapel["kode"]],
                    ["tipe", "=", "uh"],
                ])
                    ->with("tp")
                    ->get();

                $avgUh = Nilai::where([
                    ["siswa_id", "=", $queries["siswaId"]],
                    ["rombel_id", "=", $queries["rombelId"]],
                    ["tapel", "=", $queries["tapel"]],
                    ["semester", "=", $queries["semester"]],
                    ["mapel_id", "=", $mapel["kode"]],
                    ["tipe", "=", "uh"],
                ])
                    ->whereHas("tp")
                    ->whereNot("skor", 0)
                    ->avg("skor");

                $maxUh = Nilai::whereHas("tp")
                    ->where([
                        ["siswa_id", "=", $queries["siswaId"]],
                        ["rombel_id", "=", $queries["rombelId"]],
                        ["tapel", "=", $queries["tapel"]],
                        ["semester", "=", $queries["semester"]],
                        ["mapel_id", "=", $mapel["kode"]],
                        ["tipe", "=", "uh"],
                    ])
                    ->orderBy("skor", "DESC")
                    ->with("tp")
                    ->first();
                $minUh = Nilai::whereHas("tp")
                    ->where([
                        ["siswa_id", "=", $queries["siswaId"]],
                        ["rombel_id", "=", $queries["rombelId"]],
                        ["tapel", "=", $queries["tapel"]],
                        ["semester", "=", $queries["semester"]],
                        ["mapel_id", "=", $mapel["kode"]],
                        ["tipe", "=", "uh"],
                    ])
                    ->orderBy("skor", "ASC")
                    ->with("tp")
                    ->first();

                $na = ceil(($avgUh + ($nas !== null ? $nas->skor : 0)) / 2);

                $nilais[$mapel["kode"]] = [
                    "nomor" => $nomor,
                    "mapel" => $mapel,
                    "na" => $na,
                    "minu" => $minUh,
                    "maxu" => $maxUh,
                    "kktp" => $kktp,
                ];
            }

            return $nilais;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function simpanPermanen($rombelId, $tapel, $semester)
    {
        try {
            $rombel = Rombel::whereKode($rombelId)
                ->with("siswas.sekolah")
                /* ->with('gurus', function($query) { */
                /*     $query->wherePivot('status', 'wali'); */
                /* })->whereHas('gurus', function($query) { */
                /*     $query->wherePivot('status', 'wali'); */
                /* }) */
                ->whereHas("gurus", function ($query) {
                    $query->where("jabatan", "Guru Kelas");
                })
                ->with("gurus", function ($query) {
                    $query->where("jabatan", "Guru Kelas");
                })
                ->first();
            dd($rombel->label);
            $results = [];

            foreach ($rombel->siswas as $siswa) {
                $queries = [
                    "sekolahId" => $siswa->sekolah->npsn,
                    "siswaId" => $siswa->nisn,
                    "rombelId" => $rombelId,
                    "tapel" => $tapel,
                    "semester" => $semester,
                ];
                $result = [
                    "nilais" => $this->nilaiPAS($queries),
                    "absensi" => $this->absensi($queries),
                    "ekskuls" => $this->ekskul($queries),
                    "catatan" => $this->catatan($queries),
                    "tanggal" => TanggalRapor::where(
                        "semester",
                        $queries["semester"]
                    )
                        ->where("tapel", $queries["tapel"])
                        ->where("tipe", "pas")
                        ->first(),
                ];

                $arsip = Rapor::updateOrCreate(
                    [
                        "kode" =>
                            $siswa->nisn .
                            "-" .
                            $queries["tapel"] .
                            $queries["semester"],
                    ],
                    [
                        "siswa_id" => $siswa->nisn,
                        "semester" => $queries["semester"],
                        "tapel" => $queries["tapel"],
                        "tingkat" => $rombel->tingkat,
                        "guru_id" => $rombel->gurus[0]->nip,
                        "ks" => Guru::whereId($siswa->sekolah->ks_id)->pluck(
                            "nip"
                        ),
                        "tanggal_rapor" => TanggalRapor::where(
                            "semester",
                            $queries["semester"]
                        )
                            ->where("tapel", $queries["tapel"])
                            ->where("tipe", "pas")
                            ->pluck("tanggal"),
                        "rombel_id" => $rombelId,
                        "ekskuls" => $this->ekskul($queries),
                        "absensi" => $this->absensi($queries),
                        "catatan" => $this->catatan($queries),
                    ]
                );
                $checkArsip = RaporDetail::where(
                    "rapor_id",
                    $arsip->kode
                )->first();
                if ($checkArsip) {
                    foreach ($this->nilaiPAS($queries) as $nilai) {
                        RaporDetail::create([
                            "rapor_id" => $arsip->kode,
                            "mapel_id" => $nilai["mapel"],
                            "uh" => 0,
                            "ts" => 0,
                            "as" => $nilai["na"],
                            "rerata" => 0,
                        ]);
                    }
                }
                array_push($results, $result);
            }
            return $results;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function deskripsi($nilai) {}

    public function absensi($queries)
    {
        try {
            return Absensi::where([
                ["siswa_id", "=", $queries["siswaId"]],
                ["semester", "=", $queries["semester"]],
                ["rombel_id", "=", $queries["rombelId"]],
            ])->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function catatan($queries)
    {
        $catatan = Catatan::where([
            ["siswa_id", "=", $queries["siswaId"]],
            ["semester", "=", $queries["semester"]],
            ["rombel_id", "=", $queries["rombelId"]],
        ])->first();

        return $catatan ? $catatan->teks : null;
    }

    public function ekskul($queries)
    {
        try {
            return NilaiEkskul::where([
                ["tapel", "=", $queries["tapel"]],
                ["semester", "=", $queries["semester"]],
                ["siswa_id", "=", $queries["siswaId"]],
            ])
                ->with("ekskul")
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
