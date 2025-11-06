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
        // Migrasi Anda menggunakan string. Jika ini seharusnya
        // merepresentasikan 'Ya'/'Tidak', Anda bisa ganti faker->word()
        // dengan ->faker->randomElement(['Ya', 'Tidak']).
        // Saya asumsikan ini adalah string deskriptif singkat.
        return [
            'sewaktu' => $this->faker->sentence(2),
            'tempat_kerja' => $this->faker->sentence(2),
            'mandiri' => $this->faker->sentence(2),
        ];
    }
}