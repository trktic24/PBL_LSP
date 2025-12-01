<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisTuk>
 */
class JenisTukFactory extends Factory
{
    public function definition(): array
{
    return [
        'jenis_tuk' => fake()->randomElement(['Sewaktu', 'Tempat Kerja']),
    ];
}
}
