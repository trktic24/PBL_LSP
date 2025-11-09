<?php

namespace Database\Factories;

use App\Models\DataPekerjaanAsesi; // Pastiin nama model lu bener
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataPekerjaanAsesi>
 */
class DataPekerjaanAsesiFactory extends Factory
{

    protected $model = DataPekerjaanAsesi::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            // Ini ngisi kolom sesuai migrasi lu
            'id_asesi' => \App\Models\Asesi::factory(),
            'nama_institusi_pekerjaan' => fake()->company(),
            'alamat_institusi'         => fake()->address(),
            'jabatan'                  => fake()->jobTitle(),
            'kode_pos_institusi'       => fake()->postcode(),
            'no_telepon_institusi'     => fake()->numerify('081#########'), 
        ];
    }
}