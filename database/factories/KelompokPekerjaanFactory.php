<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\KelompokPekerjaan;
use App\Models\Skema;
use App\Models\UnitKompetensi; // <-- PENTING: Import model ini

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KelompokPekerjaan>
 */
class KelompokPekerjaanFactory extends Factory
{
    /**
     * Tentukan model yang terhubung.
     *
     * @var string
     */
    protected $model = KelompokPekerjaan::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_skema' => Skema::factory(),
            // Kolom 'nama_kelompok_pekerjaan'
            'nama_kelompok_pekerjaan' => fake()->jobTitle(),

        ];
    }
}
