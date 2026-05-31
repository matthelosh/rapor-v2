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
                ])
                ->first();
            $mapels = $sekolah->mapels;
            $siswa = Siswa::whereNisn($queries["siswaId"])->first();

            // Eager load semua nilai PTS sekaligus untuk menghindari N+1 query
            $nilaiPtsRaw = Nilai::where([
                ["siswa_id", "=", $queries["siswaId"]],
                ["rombel_id", "=", $queries["rombelId"]],
                ["tapel", "=", $queries["tapel"]],
                ["semester", "=", $queries["semester"]],
                ["tipe", "=", "ts"],
            ])
                ->with("mapel")
                ->get()
                ->keyBy("mapel_id");

            $nilais = ["pts" => []];
            foreach ($mapels as $mapel) {
                $npts = $nilaiPtsRaw->get($mapel["kode"]);

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
            $siswa = Siswa::where("nisn", $queries["siswaId"])->first();
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

            // Eager load semua nilai sekaligus untuk menghindari N+1 query
            $nilaiRaw = Nilai::where('siswa_id', $queries['siswaId'])
                ->where('rombel_id', $queries['rombelId'])
                ->where('tapel', $queries['tapel'])
                ->where('semester', $queries['semester'])
                ->whereIn('tipe', ['as', 'uh'])
                ->with('tp')
                ->get()
                ->groupBy('mapel_id');

            // Eager load semua Kktp sekaligus untuk menghindari N+1 query
            $kktps = Kktp::where("rombel_id", $rombel->kode)
                ->where("sekolah_id", $sekolah->npsn)
                ->where("tapel", $queries["tapel"])
                ->where("semester", $queries["semester"])
                ->whereIn("mapel_id", $mapels->pluck("kode"))
                ->get()
                ->keyBy("mapel_id");

            $nilais = [];
            $nomor = 0;
            foreach ($mapels as $mapel) {
                $nomor++;

                // Ambil kktp dari collection yang sudah di-load (bukan query baru)
                $kktp = $kktps->get($mapel->kode);

                $nas = isset($nilaiRaw[$mapel["kode"]]) ? ($nilaiRaw[$mapel["kode"]]->where('tipe', 'as')->first() ?? null) : null;

                $uhs = isset($nilaiRaw[$mapel["kode"]]) ? $nilaiRaw[$mapel['kode']]->where('tipe', 'uh')->filter(fn($n) => $n->tp && $n->skor != 0) : collect();

                $avgUh = $uhs->avg('skor') ?? 0;

                $maxUh = isset($nilaiRaw[$mapel["kode"]]) ? $nilaiRaw[$mapel['kode']]->where('tipe', 'uh')->where('agama', $mapel['kode'] == 'pabp' ? $siswa->agama : null)->filter(fn($n) => $n->tp && $n->skor != 0)->sortByDesc('skor')->first() : null;

                $minUh = isset($nilaiRaw[$mapel["kode"]]) ? $nilaiRaw[$mapel['kode']]->where('tipe', 'uh')->where('agama', $mapel['kode'] == 'pabp' ? $siswa->agama : null)->filter(fn($n) => $n->tp && $n->skor != 0)->sortBy('skor')->first() : null;

                $na = ceil(($avgUh + ($nas !== null ? $nas->skor : 0)) / 2);

                $nilais[$mapel["kode"]] = [
                    "nomor" => $nomor,
                    "mapel" => $mapel,
                    "na" => $na,
                    "avgUh" => $avgUh,
                    "nas" => $nas !== null ? $nas->skor : 0,
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
                ->with([
                    "siswas.sekolah",
                    "wali_kelas",
                    "gurus:id,nip"
                ])
                ->whereHas('wali_kelas')
                ->first();

            if (!$rombel) {
                throw new \Exception("Rombel tidak ditemukan");
            }

            // Batch load semua data yang diperlukan untuk semua siswa sekaligus
            $siswaIds = $rombel->siswas->pluck("nisn")->toArray();

            // Pre-load nilai untuk semua siswa
            $nilaiAll = Nilai::whereIn('siswa_id', $siswaIds)
                ->where('rombel_id', $rombelId)
                ->where('tapel', $tapel)
                ->where('semester', $semester)
                ->whereIn('tipe', ['as', 'uh'])
                ->with('tp')
                ->get()
                ->groupBy('siswa_id');

            // Pre-load absensi untuk semua siswa
            $absensiAll = Absensi::whereIn('siswa_id', $siswaIds)
                ->where('rombel_id', $rombelId)
                ->where('semester', $semester)
                ->get()
                ->keyBy('siswa_id');

            // Pre-load catatan untuk semua siswa
            $catatanAll = Catatan::whereIn('siswa_id', $siswaIds)
                ->where('rombel_id', $rombelId)
                ->where('semester', $semester)
                ->get()
                ->keyBy('siswa_id');

            // Pre-load ekskul untuk semua siswa
            $ekskulAll = NilaiEkskul::whereIn('siswa_id', $siswaIds)
                ->where('tapel', $tapel)
                ->where('semester', $semester)
                ->with('ekskul')
                ->get()
                ->groupBy('siswa_id');

            // Pre-load tanggal rapor
            $tanggalRapor = TanggalRapor::where("semester", $semester)
                ->where("tapel", $tapel)
                ->where("tipe", "pas")
                ->pluck("tanggal");

            $guruNip = $rombel->gurus[0]->nip ?? null;
            $ksNip = Guru::whereId($rombel->siswas->first()->sekolah->ks_id)->pluck("nip");

            foreach ($rombel->siswas as $siswa) {
                $nilaiPAS = $this->nilaiPASForSiswa($siswa, $rombel, $tapel, $semester, $nilaiAll);

                $arsip = Rapor::updateOrCreate(
                    [
                        "kode" => $siswa->nisn . "-" . $tapel . $semester,
                    ],
                    [
                        "siswa_id" => $siswa->nisn,
                        "sekolah" => $siswa->sekolah->nama,
                        "semester" => $semester,
                        "tapel" => $tapel,
                        "tingkat" => $rombel->tingkat,
                        "kelas" => $rombel->label,
                        "guru_id" => $guruNip,
                        "ks" => $ksNip,
                        "tanggal_rapor" => $tanggalRapor,
                        "rombel_id" => $rombelId,
                        "ekskuls" => $ekskulAll->get($siswa->nisn, collect()),
                        "absensi" => $absensiAll->get($siswa->nisn),
                        "catatan" => $catatanAll->get($siswa->nisn)?->teks ?? null,
                        "nilai_akademik" => $nilaiPAS,
                        "nilai_akhir" => collect($nilaiPAS)->avg("na") ?? 0,
                        "fase" => $rombel->fase,
                        "ttd" => [],
                        "status" => "permanen",
                    ]
                );

                $checkArsip = RaporDetail::where("rapor_id", $arsip->kode)->exists();
                if (!$checkArsip) {
                    $raporDetails = [];
                    foreach ($nilaiPAS as $nilai) {
                        $raporDetails[] = [
                            "rapor_id" => $arsip->kode,
                            "mapel_id" => $nilai["mapel"]["kode"],
                            "uh" => $nilai["avgUh"],
                            "ts" => 0,
                            "as" => $nilai["nas"],
                            "rerata" => $nilai["na"],
                            "created_at" => now(),
                            "updated_at" => now(),
                        ];
                    }
                    RaporDetail::insert($raporDetails);
                }
            }

            return ["message" => "Rapor berhasil disimpan secara permanen untuk rombel {$rombel->label}"];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function nilaiPASForSiswa($siswa, $rombel, $tapel, $semester, $nilaiAll)
    {
        $sekolah = $siswa->sekolah;
        $mapels = Mapel::whereHas("sekolah", function ($q) use ($sekolah) {
            $q->where("npsn", $sekolah->npsn);
        })
            ->where("fase", "LIKE", "%" . $rombel->fase . "%")
            ->orderBy("id", "ASC")
            ->get();

        $nilaiRaw = $nilaiAll->get($siswa->nisn, collect())->groupBy('mapel_id');

        // Eager load Kktp
        $kktps = Kktp::where("rombel_id", $rombel->kode)
            ->where("sekolah_id", $sekolah->npsn)
            ->where("tapel", $tapel)
            ->where("semester", $semester)
            ->whereIn("mapel_id", $mapels->pluck("kode"))
            ->get()
            ->keyBy("mapel_id");

        $nilais = [];
        $nomor = 0;
        foreach ($mapels as $mapel) {
            $nomor++;

            $kktp = $kktps->get($mapel->kode);
            $nas = isset($nilaiRaw[$mapel["kode"]]) ? ($nilaiRaw[$mapel["kode"]]->where('tipe', 'as')->first() ?? null) : null;

            $uhs = isset($nilaiRaw[$mapel["kode"]]) ? $nilaiRaw[$mapel['kode']]->where('tipe', 'uh')->filter(fn($n) => $n->tp && $n->skor != 0) : collect();

            $avgUh = $uhs->avg('skor') ?? 0;

            $maxUh = isset($nilaiRaw[$mapel["kode"]]) ? $nilaiRaw[$mapel['kode']]->where('tipe', 'uh')->where('agama', $mapel['kode'] == 'pabp' ? $siswa->agama : null)->filter(fn($n) => $n->tp && $n->skor != 0)->sortByDesc('skor')->first() : null;

            $minUh = isset($nilaiRaw[$mapel["kode"]]) ? $nilaiRaw[$mapel['kode']]->where('tipe', 'uh')->where('agama', $mapel['kode'] == 'pabp' ? $siswa->agama : null)->filter(fn($n) => $n->tp && $n->skor != 0)->sortBy('skor')->first() : null;

            $na = ceil(($avgUh + ($nas !== null ? $nas->skor : 0)) / 2);

            $nilais[$mapel["kode"]] = [
                "nomor" => $nomor,
                "mapel" => $mapel,
                "na" => $na,
                "avgUh" => $avgUh,
                "nas" => $nas !== null ? $nas->skor : 0,
                "minu" => $minUh,
                "maxu" => $maxUh,
                "kktp" => $kktp,
            ];
        }

        return $nilais;
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
