<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SoalIa05Factory extends Factory
{
    public function definition()
    {
        return [
            // --- PERBAIKAN DISINI ---
            // SALAH: 'id_data_sertifikasi_asesi' => 1,
            // BENAR: Harus 'id_skema' sesuai migration kamu
            
            // Kita ambil skema acak yang udah ada, atau bikin baru kalau kosong
            'id_skema' => \App\Models\Skema::inRandomOrder()->first()->id_skema ?? \App\Models\Skema::factory(),
            
            // ------------------------

            // Isi lainnya biarin aja (udah bener)
            'soal_ia05' => $this->faker->sentence(5) . '?', 
            'opsi_a_ia05' => $this->faker->words(3, true),
            'opsi_b_ia05' => $this->faker->words(3, true),
            'opsi_c_ia05' => $this->faker->words(3, true),
            'opsi_d_ia05' => $this->faker->words(3, true),
        ];
    }
}