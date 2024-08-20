<?php

namespace Database\Factories;

use App\Models\Tapel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agenda>
 */
class AgendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggal = fake()->dateTimeThisYear($max = '8 months', $timezone = 'Asia/jakarta');
        $tapel = Tapel::whereIsActive(1)->first();
        return [
            'nama' => \fake()->sentence(),
            'pelaksana' => \fake()->name(),
            'mulai' => $tanggal,
            'selesai' => $tanggal,
            'deskripsi' => fake()->realText(),
            'is_libur' => true,
            'warna' => 'red',
            'tapel' => $tapel->kode,
        ];
    }
}
