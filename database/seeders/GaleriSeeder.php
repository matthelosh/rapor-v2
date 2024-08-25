<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Galeri::factory(10)->create();
        // Galeri::create(
        //     [
        //         'nama' => 'Testing',
        //         'tanggal' => '2024-08-17',
        //         'kategori' => 'Kegiatan',
        //         'url' => 'https://picsum.photos/600/400',
        //         'deskripsi' => 'Kegiatan testing',
        //         'lokasi' => 'Bumi'
        //     ]
        // );
    }
}
