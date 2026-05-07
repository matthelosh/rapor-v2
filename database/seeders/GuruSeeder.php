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
        $ids = Sekolah::get()->map(fn($s) => $s->id);

        Guru::factory(50)->create()->each(function ($s) use ($ids) {
            // 'sekolahs' => $this->faker->randomElement($sekolahs->pluck('id')),
            $s->sekolahs()->attach(\fake()->randomElement($ids));
        });
    }
}
