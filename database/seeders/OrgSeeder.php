<?php

namespace Database\Seeders;

use App\Models\Kkg;
use App\Models\Org;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kkgs = [
            [
                'nama' => 'KKG Kelas 1',
            ],
            [
                'nama' => 'KKG Kelas 2',
            ],
            [
                'nama' => 'KKG Kelas 3',
            ],
            [
                'nama' => 'KKG Kelas 4',
            ],
            [
                'nama' => 'KKG Kelas 5',
            ],
            [
                'nama' => 'KKG Kelas 6',
            ],
            [
                'nama' => 'KKG PAI',
            ],
            [
                'nama' => 'KKG Hindu',
            ],
            [
                'nama' => 'KKG Kristen',
            ],
            [
                'nama' => 'KKG PJOK',
            ],
            [
                'nama' => 'KKG Seni',
            ],
            [
                'nama' => 'KKG B. Inggris',
            ],
            [
                'nama' => 'Pokja OPS',
            ],
            [
                'nama' => 'Sispres',
            ],
            [
                'nama' => 'OSN',
            ],

        ];

        foreach ($kkgs as $kkg) {
            Org::create([
                'nama' => $kkg['nama']
            ]);
        }
    }
}
