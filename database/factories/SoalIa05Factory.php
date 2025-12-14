<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SoalIa05Factory extends Factory
{
    public function definition()
    {
        return [
            // PENTING: id ini harus ada di tabel 'data_sertifikasi_asesi'
            // Jika ingin aman, ganti angka 1 dengan id yang pasti ada, 
            // atau gunakan: \App\Models\DataSertifikasiAsesi::factory(),
            'id_data_sertifikasi_asesi' => 1, 
            
            // Membuat kalimat soal random (diakhiri tanda tanya)
            'soal_ia05' => $this->faker->sentence(5) . '?', 
            
            // Membuat opsi jawaban random
            'opsi_a_ia05' => $this->faker->words(3, true),
            'opsi_b_ia05' => $this->faker->words(3, true),
            'opsi_c_ia05' => $this->faker->words(3, true),
            'opsi_d_ia05' => $this->faker->words(3, true),
        ];
    }
}