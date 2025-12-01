<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DataPekerjaanAsesi;

class DataPekerjaanAsesiFactory extends Factory
{
    protected $model = DataPekerjaanAsesi::class;

    public function definition(): array
    {
        return [
            // Kita tidak perlu mengisi 'id_asesi' di sini, 
            // karena akan diisi otomatis oleh AsesiFactory
            'nama_institusi_pekerjaan' => fake()->company(),
            'alamat_institusi' => fake()->address(),
            'jabatan' => fake()->jobTitle(),
            'kode_pos_institusi' => fake()->postcode(),
            'no_telepon_institusi' => fake()->numerify('08###########'),
        ];
    }
}