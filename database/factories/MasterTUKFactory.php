<?php

namespace Database\Factories;

use App\Models\MasterTuk;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterTUKFactory extends Factory
{
    protected $model = MasterTuk::class;

    public function definition(): array
    {
        return [
            'nama_lokasi' => 'TUK ' . fake()->company(),
            'alamat_tuk' => fake()->address(),
            'kontak_tuk' => fake()->phoneNumber(),
            'foto_tuk' => 'path/to/fake_foto_tuk.jpg',
            'link_gmap' => fake()->url(),
        ];
    }
}