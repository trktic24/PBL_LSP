<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DataSertifikasiAsesi; // Pastikan model ini ada

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UmpanBalikIa06>
 */
class UmpanBalikIa06Factory extends Factory
{
    public function definition(): array
    {
        return [
            // OPSI 1: Jika model DataSertifikasiAsesi punya factory juga
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),

            'umpan_balik' => $this->faker->paragraph(),
        ];
    }
}