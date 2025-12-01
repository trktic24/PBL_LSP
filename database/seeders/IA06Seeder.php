<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoalIA06;
use App\Models\KunciIA06;

class IA06Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat 10 Soal Master
        // Kita simpan ke variabel agar bisa dipakai untuk membuat jawaban
        $soals = SoalIA06::factory()->count(10)->create();

        // 2. Untuk setiap soal yang dibuat, buatkan jawaban dumy dari Asesi (ID 1)
        foreach ($soals as $soal) {
            KunciIA06::factory()->create([
                'id_soal_ia06' => $soal->id_soal_ia06, // Pakai ID soal yang baru dibuat
                'id_data_sertifikasi_asesi' => 1,      // Simulasi Asesi ID 1
                'teks_jawaban_ia06' => 'Ini adalah contoh jawaban asesi untuk soal: ' . substr($soal->soal_ia06, 0, 20) . '...',
            ]);
        }
    }
}