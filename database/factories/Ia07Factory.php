<?php

namespace Database\Factories;

use App\Models\Ia07;
use App\Models\DataSertifikasiAsesi; // Pastikan model ini ada
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ia07>
 */
class Ia07Factory extends Factory
{
    /**
     * Model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = Ia07::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Menggunakan factory DataSertifikasiAsesi untuk mendapatkan ID yang valid
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),
            'pertanyaan' => fake()->sentence(10) . '?',
            'jawaban_asesi' => fake()->sentence(8),
            'jawaban_diharapkan' => fake()->sentence(8),
            'pencapaian' => fake()->boolean(), // Menghasilkan nilai true (1) atau false (0)
        ];
    }
}