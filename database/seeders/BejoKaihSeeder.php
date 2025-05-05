<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Kaih;

class BejoKaihSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bejo = Siswa::whereNama("Bejo Kumayangan")->first();
        Kaih::create([
            "rombel_id" => $bejo->rombels[0]->kode,
            "siswa_id" => $bejo->nisn,
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
