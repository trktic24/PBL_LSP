<?php

namespace Database\Factories;

use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkemaFactory extends Factory
{
    protected $model = Skema::class;

    public function definition(): array
    {
        return [
            // Kolom ini disesuaikan dengan migrasi 'skema' Anda
            'kode_unit' => fake()->bothify('?.####.#####.##'), // cth: J.62010.001.01
            'nama_skema' => fake()->jobTitle(),
            'deskripsi_skema' => fake()->paragraph(),
            'SKKNI' => 'path/to/dummy-skkni.pdf',
            'gambar' => 'path/to/dummy-gambar.png',
        ];
    }
}