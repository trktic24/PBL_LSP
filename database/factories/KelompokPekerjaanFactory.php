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
     * 3. WAJIB: Tentukan Model yang dipake
     */
    protected $model = KelompokPekerjaan::class;

    /**
     * 4. Definisikan data palsunya
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // --- INI KUNCINYA ---
            // Ambil 'id_skema' SECARA ACAK dari tabel 'skema'
            // Ini WAJIB dijalanin SETELAH SkemaFactory
            'id_skema' => Skema::all()->random()->id_skema,
            
            // Bikin data palsu sesuai migrasi lu
            'kode_unit' => 'K.' . fake()->numerify('450100.0##.0#'),
            'judul_unit' => 'Unit ' . fake()->words(3, true),
        ];
    }
}