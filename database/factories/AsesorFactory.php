<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Skema;
use App\Models\User;

class AsesorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // ✅ tambahkan ini
            'id_skema' => Skema::factory(),
            'nomor_regis' => 'MET.' . fake()->randomNumber(7, true),
            'nama_lengkap' => fake()->name(),
            'nik' => fake()->numerify('################'),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'kebangsaan' => 'Indonesia',
            'pekerjaan' => fake()->jobTitle(),
            'alamat_rumah' => fake()->address(),
            'kode_pos' => fake()->postcode(),
            'kabupaten_kota' => fake()->city(),
            'provinsi' => fake()->state(),
            'nomor_hp' => substr($this->faker->phoneNumber(), 0, 14),
            'NPWP' => fake()->numerify('##.###.###.#-###.###'),
            'nama_bank' => fake()->randomElement(['BNI', 'BRI', 'BCA', 'Mandiri']),
            'norek' => fake()->creditCardNumber(),

            // File dummy
            'ktp' => null,
            'pas_foto' => null,
            'NPWP_foto' => null,
            'rekening_foto' => null,
            'CV' => null,
            'ijazah' => null,
            'sertifikat_asesor' => null,
            'sertifikasi_kompetensi' => null,
            'tanda_tangan' => null,

            'is_verified' => true,
        ];
    }
}
