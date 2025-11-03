<?php

namespace Database\Factories;

use App\Models\Asesi; // <-- 1. JANGAN LUPA USE MODELNYA
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Asesi> // <-- 2. BENERIN INI
 */
class AsesiFactory extends Factory
{
    /**
     * 3. TAMBAHIN INI (PALING PENTING)
     * Nentuin model yang dipake sama factory ini.
     */
    protected $model = Asesi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 4. PASTIIN DEFINISINYA KEISI
        // (Ini kode dari obrolan kita sebelumnya)
        return [
            'id_user' => \App\Models\User::factory(),
            'nama_lengkap'    => fake('id_ID')->name(),
            'nik'             => fake()->unique()->numerify('################'), 
            'tempat_lahir'    => fake('id_ID')->city(),
            'tanggal_lahir'   => fake()->date('Y-m-d', '2005-01-01'),
            'jenis_kelamin'   => fake()->boolean(),
            'kebangsaan'      => 'Indonesia',
            'pendidikan'      => fake()->randomElement(['SMK', 'SMA', 'D3', 'S1']),
            'pekerjaan'       => fake('id_ID')->jobTitle(),
            'alamat_rumah'    => fake('id_ID')->address(),
            'kode_pos'        => fake('id_ID')->postcode(),
            'kabupaten_kota'  => fake('id_ID')->city(),
            'provinsi'        => fake('id_ID')->state(),
            'nomor_hp'        => fake('id_ID')->phoneNumber(),
            'tanda_tangan'    => null, 
        ];
    }
}