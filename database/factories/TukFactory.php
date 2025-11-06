<?php

namespace Database\Factories;

use App\Models\Tuk;
use Illuminate\Database\Eloquent\Factories\Factory;

class TukFactory extends Factory
{
    protected $model = Tuk::class;

    public function definition(): array
    {
        // Data ini akan mengisi tabel 'master_tuk' Anda
        return [
            'nama_lokasi' => fake()->company(),
            'alamat_tuk' => fake()->address(),
            'kontak_tuk' => fake()->phoneNumber(),
            'foto_tuk' => 'path/to/dummy_tuk.jpg',
            'link_gmap' => 'https://maps.google.com/',
        ];
    }
}