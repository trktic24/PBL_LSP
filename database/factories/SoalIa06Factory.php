<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SoalIa06Factory extends Factory
{
    public function definition(): array
    {
        return [
            // Membuat kalimat pertanyaan acak
            'soal_ia06'          => $this->faker->sentence(10) . '?',
            // Membuat kunci jawaban dummy
            'kunci_jawaban_ia06' => $this->faker->paragraph(),
        ];
    }
}