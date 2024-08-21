<?php

namespace Database\Seeders;

use App\Models\p5;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class p5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dimensis = [
            [
                'kode' => 'D1',
                'dimensi' => 'Beriman, Bertakwa Kepada Tuhan Yang Maha Esa, dan Berahlak Mulia',
            ],
            [
                'kode' => 'D2',
                'dimensi' => 'Berkebhinekaan Global'
            ],
            [
                'kode' => 'D3',
                'dimensi' => 'Bergotong Royong'
            ],
            [
                'kode' => 'D4',
                'dimensi' => 'Mandiri'
            ],
            [
                'kode' => 'D5',
                'dimensi' => 'Bernalar Kritis'
            ],
            [
                'kode' => 'D6',
                'dimensi' => 'Kreatif'
            ],
        ];

        foreach ($dimensis as $p5) {
            p5::create([
                'kode' => $p5['kode'],
                'dimensi' => $p5['dimensi']
            ]);
        }
    }
}
