<?php

namespace Database\Factories;

use App\Models\Tuk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterTuk>
 */
class TukFactory extends Factory
{
    /**
     * Tentukan model yang terhubung.
     */
    protected $model = Tuk::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lokasi' => $this->faker->company(),
            'alamat_tuk'  => $this->faker->address(),
            'kontak_tuk'  => $this->faker->phoneNumber(),
            'link_gmap'   => 'https://maps.google.com/?q=' . $this->faker->latitude() . ',' . $this->faker->longitude(),

            // INI YANG DISESUAIKAN:
            // Kita hanya menghasilkan SATU string URL,
            // karena kolom 'foto_tuk' adalah string, bukan array/json.
            'foto_tuk'    => $this->faker->imageUrl(640, 480, 'building', true),
        ];
    }
}
