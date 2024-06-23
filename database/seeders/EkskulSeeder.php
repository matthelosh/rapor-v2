<?php

namespace Database\Seeders;

use App\Models\Ekskul;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EkskulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ekskuls = [
            [
                'kode' => 'prm',
                'nama' => 'Pramuka',
                'keterangan' => 'Ekstrakurikuler Pramuka',
                'sifat' => 'Wajib'
            ],
            [
                'kode' => 'btq',
                'nama' => 'Baca Tulis Al-Quran',
                'keterangan' => 'Ekstrakurikuler PAI TBTQ',
                'sifat' => 'Pilihan'
            ],
            [
                'kode' => 'drb',
                'nama' => 'Drumband',
                'keterangan' => 'Ekstrakurikuler Drumband',
                'sifat' => 'Pilihan'
            ],
            [
                'kode' => 'bjr',
                'nama' => 'Banjari',
                'keterangan' => 'Ekstrakurikuler Keagamaan Banjari',
                'sifat' => 'Pilihan'
            ],
            [
                'kode' => 'sb',
                'nama' => 'Sepak Bola',
                'keterangan' => 'Ekstrakurikuler PJOK Sepak Bola',
                'sifat' => 'Pilihan'
            ],
            [
                'kode' => 'ps',
                'nama' => 'Pencak Silat',
                'keterangan' => 'Ekstrakurikuler PJOK dan Seni Pencak Silat',
                'sifat' => 'Pilihan'
            ],
            [
                'kode' => 'tr',
                'nama' => 'Seni Tari',
                'keterangan' => 'Ekstrakurikuler Seni Tari',
                'sifat' => 'Pilihan'
            ],
        ];

        foreach ($ekskuls as $ekskul) {
            Ekskul::create([
                'kode' => $ekskul['kode'],
                'nama' => $ekskul['nama'],
                'keterangan' => $ekskul['keterangan'],
                'sifat' => $ekskul['sifat'],
                'is_active' => true
            ]);
        }
    }
}
