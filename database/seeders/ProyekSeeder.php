<?php

namespace Database\Seeders;

use App\Models\Proyek;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Proyek::create(
            [
                'tapel' => '2425',
                'semester' => '1',
                'sekolah_id' => '20518848',
                'rombel_id' => '20518848-2425-5',
                'nama' => 'Proyek Percobaan P5 Kelas 5 ',
                'deskripsi' => 'Proyek ini hanya untuk testing',
                'status' => 'rencana'
            ]
        );
    }
}
