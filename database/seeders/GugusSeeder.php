<?php

namespace Database\Seeders;

use App\Models\Gugus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GugusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 6; $i++) {
            Gugus::create([
                'nama' => 'Gugus ' . $i,
                'deskripsi' => 'Gugus ' . $i,
            ]);
        }
    }
}
