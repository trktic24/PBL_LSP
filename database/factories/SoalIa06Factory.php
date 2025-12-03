<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SoalIa06>
 */
class SoalIa06Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Membuat kalimat tanya acak
            'soal_ia06'          => $this->faker->sentence(10) . '?',

            // Default jawaban asesi kosong dulu (karena ini bank soal)
            'isi_jawaban_ia06'   => null,

            // Pencapaian belum dinilai
            'pencapaian'         => null,

            // Kunci jawaban diisi paragraf acak
            'kunci_jawaban_ia06' => $this->faker->paragraph(),
        ];
    }
}