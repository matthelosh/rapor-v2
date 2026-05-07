<?php

namespace Database\Seeders;

use App\Models\Jilid;
use App\Models\Sekolah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JilidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sekolahs = Sekolah::all();
        foreach ($sekolahs as $sekolah) {
            for ($i = 0; $i < 3; $i++) {
                Jilid::create(
                    [
                        'sekolah_id' => $sekolah->npsn,
                        'nama' => 'Jilid ' . $i + 1,
                        'juz' => $i + 1
                    ]
                );
            }
        }
    }
}
