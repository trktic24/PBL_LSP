<?php

namespace Database\Factories;

use App\Models\Penyusun;
use Illuminate\Database\Eloquent\Factories\Factory;

class ValidatorFactory extends Factory
{
    /**
     * Nama model yang terkait dengan factory ini.
     */
    protected $model = Penyusun::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // Membuat nama random dengan gelar
            'nama_penyusun' => $this->faker->name() . ', ' . $this->faker->randomElement(['S.Kom', 'M.Pd', 'S.T', 'M.M']),

            // Membuat nomor MET random (Format: MET.XXX.2025)
            'no_MET_penyusun' => 'MET.' . $this->faker->numerify('###') . '.' . date('Y'),

            // TTD dikosongkan (null)
            'ttd' => null,
        ];
    }
}