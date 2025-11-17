<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class KriteriaUnjukKerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_kriteria' => fake()->numerify('#.#'),
            'kriteria' => fake()->sentence(8),
            'tipe' => 'demonstrasi',
        ];
    }
}
