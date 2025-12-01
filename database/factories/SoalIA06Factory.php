<?php

namespace Database\Factories;

use App\Models\SoalIA06;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoalIA06Factory extends Factory
{
    /**
     * Tentukan model yang terkait dengan factory ini.
     */
    protected $model = SoalIA06::class;

    public function definition(): array
    {
        return [
            // Generate kalimat pertanyaan random
            'soal_ia06' => $this->faker->sentence(10) . '?',

            // Generate paragraf kunci jawaban random
            'kunci_jawaban_ia06' => $this->faker->paragraph(3),
        ];
    }
}