<?php

namespace Database\Factories;

use App\Models\UnitKompetensi;     // <-- 1. Panggil Model-nya
use App\Models\KelompokPekerjaan; // <-- 2. Panggil Model KelompokPekerjaan (buat FK)
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitKompetensi>
 */
class UnitKompetensiFactory extends Factory
{
    /**
     * 3. WAJIB: Tentukan Model yang dipake
     */
    protected $model = UnitKompetensi::class;

    /**
     * 4. Definisikan data palsunya
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // --- INI KUNCINYA ---
            // Ambil 'id_kelompok_pekerjaan' SECARA ACAK dari tabel 'kelompok_pekerjaans'
            // Ini WAJIB dijalanin SETELAH KelompokPekerjaanFactory
            'id_kelompok_pekerjaan' => KelompokPekerjaan::all()->random()->id_kelompok_pekerjaan,
            
            // Bikin data palsu sesuai migrasi lu
            'kode_unit' => 'M.' . fake()->numerify('700200.0##.0#'),
            'judul_unit' => 'Mengembangkan ' . fake()->words(3, true),
            'jenis_standar' => fake()->randomElement(['SKKNI', 'Standar Internasional', 'Standar Khusus']),
        ];
    }
}