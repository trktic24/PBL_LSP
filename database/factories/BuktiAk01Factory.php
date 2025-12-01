<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BuktiAk01>
 */
class BuktiAk01Factory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Contoh isi bukti: "Fotokopi KTP" atau kalimat acak
            'bukti' => $this->faker->sentence(3),
        ];
    }
}
