<?php

namespace Database\Factories;

use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class GuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'nip' => $this->faker->randomNumber(9),
            'nuptk' => $this->faker->randomNumber(4),
            'gelar_belakang' => $this->faker->randomElement(['S. Pd.','S.PdI.', 'S.Pd., M.Pd.', 'S.Pd.SD']),
            'nama' => $this->faker->name(),
            'jk' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'agama' => $this->faker->randomElement(['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu']),
            'jabatan' => $this->faker->randomElement(['Guru Kelas', 'Kepala Sekolah', 'Guru PJOK', 'Guru Inggris']),
            'hp' => $this->faker->randomNumber(9),
            'alamat' => $this->faker->address(),
            'status' => $this->faker->randomElement(['pns', 'p3k','gtt']),
            'email' => $this->faker->email(),
            
        ];
    }
}
