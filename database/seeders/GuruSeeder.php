<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sekolahs = Sekolah::all();
        $ids = [];
        foreach($sekolahs as $sekolah) {
            array_push($ids, $sekolah->id);
        }

        Guru::factory(50)->create()->each(function($s) use($ids) {
            // 'sekolahs' => $this->faker->randomElement($sekolahs->pluck('id')),
            $s->sekolahs()->attach(array_rand($ids,1));
        });
    }
}
