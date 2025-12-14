<?php

namespace Database\Factories;

use App\Models\KunciIA06;
use App\Models\SoalIA06;
use Illuminate\Database\Eloquent\Factories\Factory;

class KunciIA06Factory extends Factory
{
    /**
     * Tentukan model yang terkait dengan factory ini.
     */
    protected $model = KunciIA06::class;

    public function definition(): array
    {
        return [
            // Ambil ID soal secara random dari factory Soal, atau buat baru
            'id_soal_ia06' => SoalIA06::factory(),

            // Karena saya tidak punya model DataSertifikasiAsesi,
            // saya set random angka 1-10 untuk simulasi ID Asesi.
            // Nanti bisa diganti: 'id_data_sertifikasi_asesi' => \App\Models\DataSertifikasiAsesi::factory(),
            'id_data_sertifikasi_asesi' => $this->faker->numberBetween(1, 10),

            // Jawaban peserta
            'teks_jawaban_ia06' => $this->faker->paragraph(2),
        ];
    }
}