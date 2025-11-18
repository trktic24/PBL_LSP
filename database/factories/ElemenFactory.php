<?php

namespace Database\Factories;

use App\Models\UnitKompetensi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ElemenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_unit_kompetensi' => UnitKompetensi::factory(),
            'elemen' => fake()->sentence(6),
        ];
    }
}
