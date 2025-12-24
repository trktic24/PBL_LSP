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
            'foto_tuk' => 'foto_tuk/gedung' . fake()->numberBetween(1, 3) . '.jpg',
            'link_gmap' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.6350799753595!2d110.43275977577413!3d-7.052095269106962!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708c0396ceec97%3A0x10b388f0c8411e72!2sPoliteknik%20Negeri%20Semarang%20(POLINES)!5e0!3m2!1sid!2sid!4v1766558773995!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade',
        ];
    }
}