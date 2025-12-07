<?php

namespace Database\Factories;

use App\Models\Elemen;
use App\Models\UnitKompetensi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Elemen>
 */
class ElemenFactory extends Factory
{
    protected $model = Elemen::class;

    public function definition(): array
    {
        return [
            // --- INI PERBAIKANNYA ---
            // Sama kayak tadi, kita panggil factory induknya.
            // Ini akan OTOMATIS membuat 1 UnitKompetensi baru
            // (yang juga otomatis bikin 1 KelompokPekerjaan baru, dst.)
            // lalu pakai ID-nya untuk 'id_unit_kompetensi'.
            'id_unit_kompetensi' => UnitKompetensi::factory(),
            
            // Bikin data palsu (saya pakai $this->faker yg lebih aman)
            'elemen' => 'Melaksanakan ' . $this->faker->words(2, true),
        ];
    }
}