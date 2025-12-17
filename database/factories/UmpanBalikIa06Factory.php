<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UmpanBalikIa06Factory extends Factory
{
    public function definition(): array
    {
        return [
            'id_data_sertifikasi_asesi' => null, // Diisi oleh Seeder
            'umpan_balik'               => 'Hasil observasi menunjukkan asesi kompeten, namun perlu memperdalam materi X.',
        ];
    }
}