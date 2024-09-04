<?php

namespace Database\Seeders;

use App\Models\NilaiP5;
use App\Models\Proyek;
use App\Models\Rombel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NilaiP5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rombel = Rombel::whereKode('20518848-2425-3')->with('siswas')->first();
        $proyek = Proyek::whereNama('Tes #4')->with('apds')->first();


        foreach ($proyek->apds as $apd) {


            foreach ($rombel->siswas as $siswa) {
                NilaiP5::create(
                    [
                        'proyek_id' => $proyek->id,
                        'siswa_id' => $siswa->nisn,
                        'rombel_id' => $rombel->kode,
                        'tapel' => '2425',
                        'semester' => '1',
                        'apd_id' => $apd->id,
                        'nilai' => \fake()->randomElement(['BB', 'MB', 'BSH', 'SB']),
                        'keterangan' => ''
                    ]
                );
            }
        }
    }
}
