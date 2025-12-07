<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IA03;
use App\Models\DataSertifikasiAsesi;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IA03>
 */
class IA03Factory extends Factory
{
    protected $model = IA03::class;

    public function definition(): array
    {
        return [
            // FK: ambil ID DataSertifikasiAsesi random (pastikan sudah ada datanya)
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::inRandomOrder()->value('id_data_sertifikasi_asesi'),

            'pertanyaan' => $this->faker->sentence(10) . '?', // Menghasilkan pertanyaan contoh
            'tanggapan' => $this->faker->paragraph(2),

            'pencapaian' => $this->faker->randomElement([true, false, null]),

            'catatan_umpan_balik' => $this->faker->optional()->sentence(),
        ];
    }
}
