<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Berita>
 */
class BeritaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(6), // Judul acak 6 kata
            'isi' => $this->faker->paragraphs(3, true), // Isi berita 3 paragraf
            'gambar' => null, // Default gambar kosong dulu
        ];
    }
}