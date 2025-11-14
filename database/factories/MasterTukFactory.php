<?php

namespace Database\Factories;

<<<<<<< HEAD
use App\Models\MasterTuk;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterTukFactory extends Factory
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
=======
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterTuk>
 */
class MasterTukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lokasi' => $this->faker->company() . ' Training Center',
            'alamat_tuk' => $this->faker->address(),
            'kontak_tuk' => $this->faker->phoneNumber(),
            'foto_tuk' => $this->faker->imageUrl(640, 480, 'business'), // path ke foto
            'link_gmap' => $this->faker->url(), // link google maps
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
        ];
    }
}