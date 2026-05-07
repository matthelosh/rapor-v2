<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workshop>
 */
class WorkshopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mulai = fake()->dateTimeBetween('2024-01-01', '2024-12-31', 'Asia/Jakarta');
        $nama = fake()->randomElement(['Workshop', 'Bimtek', 'Pelatihan', 'Pembinaan', 'Seminar']);
        return [
            'nama' => $nama . " " . fake()->word(3),
            'lokasi' => fake('id_ID')->address(),
            'deskripsi' => fake('id_ID')->realText(),
            'pelaksana' => fake('id_ID')->name(),
            'nara_sumber' => fake('id_ID')->name(),
            'mulai' => $mulai,
            'selesai' => Carbon::create($mulai)->addDays(3),
        ];
    }
}
