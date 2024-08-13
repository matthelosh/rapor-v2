<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'category' => fake()->randomElement(['Berita', 'Info']),
            'type' => 'post',
            'slug'  => fake()->slug(),
            'cover' => 'https://loremflickr.com/320/240/nature/',
            'content' => fake()->realText(1000),
            'user_id' => fake()->randomElement(User::get()->pluck('id')),
        ];
    }
}
