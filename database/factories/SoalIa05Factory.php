<?php

namespace Database\Factories;

use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoalIa05Factory extends Factory
{
    public function definition(): array
    {
        // Ambil satu ID Skema secara acak, atau buat jika belum ada.
        // Ini PENTING agar soal punya induk.
        $idSkema = Skema::inRandomOrder()->first()->id_skema ?? Skema::factory()->create()->id_skema;

        return [
            'id_skema' => $idSkema,
            // Placeholder: Nilai ini nanti akan ditimpa oleh Seeder dengan data riil
            'soal_ia05' => 'Placeholder Pertanyaan',
            'opsi_jawaban_a' => 'Opsi A',
            'opsi_jawaban_b' => 'Opsi B',
            'opsi_jawaban_c' => 'Opsi C',
            'opsi_jawaban_d' => 'Opsi D',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}