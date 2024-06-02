<?php

namespace Database\Factories;

use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sekolahs = Sekolah::all();
        return [
            'nisn' => $this->faker->randomNumber(9),
            'nis' => $this->faker->randomNumber(4),
            'nik' => $this->faker->nik(),
            'nama' => $this->faker->name(),
            'jk' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'agama' => $this->faker->randomElement(['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu']),
            'alamat' => $this->faker->address(),
            'sekolah_id' => $this->faker->randomElement($sekolahs->pluck('npsn'))
        ];
    }
}
