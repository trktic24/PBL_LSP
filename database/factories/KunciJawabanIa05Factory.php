<?php

namespace Database\Factories;

use App\Models\SoalIa05;
use Illuminate\Database\Eloquent\Factories\Factory;

class KunciJawabanIa05Factory extends Factory
{
    public function definition(): array
    {
        return [
            // Placeholder: ID soal akan diisi oleh seeder agar pasangannya benar
            'id_soal_ia05' => SoalIa05::factory(), 
            'jawaban_benar' => 'a', // Default sementara
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}