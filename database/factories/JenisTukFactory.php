<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisTuk>
 */
class JenisTukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenis_tuk' => fake()->randomElement([
                'Sewaktu',
                'Tempat Kerja',
            ]),          
        ];
    }
}
