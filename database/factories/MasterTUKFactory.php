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
            'nama_lokasi' => 'TUK ' . $this->faker->company(),
            'alamat_tuk' => $this->faker->address(),
            'kontak_tuk' => $this->faker->phoneNumber(),
            'foto_tuk' => 'path/to/fake_foto_tuk.jpg',
            'link_gmap' => $this->faker->url(),
        ];
    }
}