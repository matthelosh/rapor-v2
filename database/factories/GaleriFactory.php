<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Galeri>
 */
class GaleriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggal = fake()->dateTimeThisYear($max = '8 months', $timezone = 'Asia/jakarta');
        return [
            'nama' => fake()->words(3, true),
            'tanggal' => $tanggal,
            'kategori' => 'Kegiatan',
            'url' => 'https://picsum.photos/id/' . fake()->randomNumber(2) . '/600/400',
            'deskripsi' => fake()->realText(),
            'lokasi' => fake()->address()
        ];
    }
}
