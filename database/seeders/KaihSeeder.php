<?php

namespace Database\Seeders;

use App\Helpers\Periode;
use App\Models\Kaih;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;

class KaihSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswas = Siswa::whereStatus("aktif")
            ->whereHas("rombels")
            ->with([
                "rombels" => function ($r) {
                    $r->where("tapel", Periode::tapel()->kode);
                },
            ])
            ->get();

        foreach ($siswas as $siswa) {
            Kaih::create([
                "rombel_id" => $siswa->rombels[0]->kode,
                "siswa_id" => $siswa->nisn,
                "semester" => fake()->randomElement(["1"]),
                "kebiasaan" => fake()->randomElement([
                    "Bangun Pagi",
                    "Berolahraga",
                    "Makan Sehat dan Bergizi",
                    "Gemar Belajar",
                    "Bermasyarakat",
                    "Tidur Cepat",
                ]),
                "is_done" => true,
                "waktu" => fake()->dateTimeBetween("-3 week", "+1 week"),
                "keterangan" => fake()->sentence(3),
            ]);
        }
    }
}
