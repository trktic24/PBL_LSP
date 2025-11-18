<?php

namespace Database\Factories;

use App\Models\Elemen;            // <-- 1. Panggil Model-nya
use App\Models\UnitKompetensi;  // <-- 2. Panggil Model UnitKompetensi (buat FK)
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Elemen>
 */
class ElemenFactory extends Factory
{
    /**
     * 3. WAJIB: Tentukan Model yang dipake
     */
    protected $model = Elemen::class;

    /**
     * 4. Definisikan data palsunya
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // --- INI KUNCINYA ---
            // Ambil 'id_unit_kompetensi' SECARA ACAK dari tabel 'master_unit_kompetensi'
            // Ini WAJIB dijalanin SETELAH UnitKompetensiFactory
            'id_unit_kompetensi' => UnitKompetensi::all()->random()->id_unit_kompetensi,
            
            // Bikin data palsu sesuai migrasi lu
            'no_elemen' => fake()->numerify('#.##'),
            'elemen' => 'Melaksanakan ' . fake()->words(2, true),
        ];
    }
}