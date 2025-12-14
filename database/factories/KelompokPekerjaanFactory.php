<?php

namespace Database\Factories;

use App\Models\KelompokPekerjaan; // <-- 1. Panggil Model-nya
use App\Models\Skema;             // <-- 2. Panggil Model Skema (buat FK)
use Illuminate\Database\Eloquent\Factories\Factory;

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
            // --- INI KUNCINYA ---
            // Ambil 'id_skema' SECARA ACAK dari tabel 'skema'
            // Ini WAJIB dijalanin SETELAH SkemaFactory
            'id_skema' => Skema::inRandomOrder()->first()->id_skema ?? Skema::factory(),
            'nama_kelompok_pekerjaan' => fake()->jobTitle(),
        ];
    }
}