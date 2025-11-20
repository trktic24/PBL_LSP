<?php

namespace Database\Factories;

// [1] Panggil Model-model yang diperlukan
use App\Models\UnitKompetensi;  // <-- Model kamu
use App\Models\KelompokPekerjaan; // <-- Model Induk (Parent)
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitKompetensi>
 */
class UnitKompetensiFactory extends Factory
{
    /**
     * Tentukan model yang sesuai dengan factory ini.
     * [ASUMSI]: Model-mu 'UnitKompetensi' nembak ke tabel 'master_unit_kompetensi'
     */
    protected $model = UnitKompetensi::class;

    /**
     * Definisikan data palsunya.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // --- INI KUNCINYA ---
            // Panggil factory induknya.
            // Ini akan OTOMATIS membuat 1 KelompokPekerjaan baru
            // lalu pakai ID-nya untuk 'id_kelompok_pekerjaan'.
            'id_kelompok_pekerjaan' => KelompokPekerjaan::factory(),
            
            // --- Mengisi kolom sesuai migrasi 'master_unit_kompetensi' ---
            
            'kode_unit' => $this->faker->unique()->bothify('J.620100.###.##'),
            
            'judul_unit' => 'Mengembangkan ' . $this->faker->words(3, true),

            // [FIX] 'jenis_standar' SEKARANG ADA
            'jenis_standar' => $this->faker->randomElement(['SKKNI', 'Standar Internasional', 'Standar Khusus']),
        ];
    }
}