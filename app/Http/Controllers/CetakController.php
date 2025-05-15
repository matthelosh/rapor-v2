<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Http\Request;

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
}
