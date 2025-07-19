<?php

namespace App\Http\Controllers;

use App\Models\Asesmen;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\Tapel;
use App\Models\Semester;

class CetakController extends Controller
{
    public function cetakTranskrip(Request $request, $nisn)
    {
        try {
            $siswa = Siswa::whereNisn($nisn)->first();
            $guru = $request->user()->userable; #type: ignore
            $sekolah = Sekolah::whereHas("gurus", function ($g) use ($guru) {
                $g->where("guru_sekolah.guru_id", $guru->id);
            })
                ->with("ks")
                ->first();
            $nilais = [
                "Pendidikan Agama dan Budi Pekerti" => fake()->numberBetween(
                    80,
                    98
                ),
                "Pendidikan Pancasila" => fake()->numberBetween(80, 98),
                "Bahasa Indonesia" => fake()->numberBetween(80, 98),
                "Matematika" => fake()->numberBetween(80, 98),
                "Ilmu Pengetahuan Alam dan Sosial" => fake()->numberBetween(
                    80,
                    98
                ),
                "Seni Rupa" => fake()->numberBetween(80, 98),
                "Pendidikan Jasmani, Olahraga, dan Kesehatan" => fake()->numberBetween(
                    80,
                    98
                ),
                "Bahasa Inggris" => fake()->numberBetween(80, 98),
                "Bahasa Jawa" => fake()->numberBetween(80, 98),
            ];
            return view("cetak.transkrip", [
                "sekolah" => $sekolah,
                "siswa" => $siswa,
                "nilais" => $nilais,
            ]);
            // dd($sekolah);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function cetakAnalisisAsesmen(Request $request, $asesmenId)
    {
        $asesmen = Asesmen::where("kode", $asesmenId)->first();
        /* dd($asesmen); */
        return view("cetak.analisis_asesmen", [
            "asesmen" => $asesmen,
        ]);
    }

    public function cetakRekapSekolahRombelSiswa(Request $request)
    {
        $tapel = Tapel::whereIsActive(1)->first();
        $tapelId = $tapel->kode;
        $sekolahs = Sekolah::whereNot("npsn", "20518473")
            ->with([
                "rombels" => function ($r) use ($tapelId) {
                    $r->where("tapel", $tapelId)->with("siswas", function ($s) {
                        $s->where("status", "aktif");
                    });
                },
                "siswas" => function ($s) {
                    $s->where("status", "aktif");
                },
            ])
            ->get();

        return view("cetak.rekap.sekolahrombelsiswa", [
            "sekolahs" => $sekolahs,
        ]);
    }

    public function cetakPiagamRanking(Request $request) {
        try {
            // "nisn" => "3137260700"
            // "rank" => "1"
            // "nilai" => "824"
            // "semester" => "1"
            $kodeRombel = $request->query('rombel');
            // "tapel" => "2425"
            return view("cetak.ledger.piagam_ranking", [
                'siswa' => Siswa::where('nisn', $request->query('nisn'))->with([
                    'sekolah.ks',
                ])->first(),
                'rombel' => Rombel::where('kode', $kodeRombel)->with('wali_kelas')->first(),
                'rank' => $request->query('rank'),
                'nilai' => $request->query('nilai'),
                'tapel' => Tapel::where('kode', $request->query('tapel'))->first(),
                'semester' => Semester::where('kode', $request->query('semester'))->first(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
