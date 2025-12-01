<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsesorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nomor_regis' => 'MET.' . fake()->unique()->numberBetween(1000000, 9999999),
            'nama_lengkap' => fake()->name(),
            'nik' => fake()->unique()->numerify('################'),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'kebangsaan' => 'Indonesia',
            'pekerjaan' => fake()->jobTitle(),
            'alamat_rumah' => fake()->address(),
            'kode_pos' => fake()->postcode(),
            'kabupaten_kota' => fake()->city(),
            'provinsi' => fake()->state(),
            'nomor_hp' => '+62' . fake()->numerify('8##########'),
            'NPWP' => fake()->numerify('##.###.###.#-###.###'),
            'nama_bank' => fake()->randomElement(['Mandiri', 'BCA', 'BRI']),
            'norek' => fake()->numerify('################'),

            // file dummy
            'ktp' => 'uploads/ktp/dummy.png',
            'pas_foto' => 'uploads/pasfoto/dummy.png',
            'NPWP_foto' => 'uploads/npwp/dummy.png',
            'rekening_foto' => 'uploads/rekening/dummy.png',
            'CV' => 'uploads/cv/dummy.pdf',
            'ijazah' => 'uploads/ijazah/dummy.pdf',
            'sertifikat_asesor' => 'uploads/sertifikat/dummy.pdf',
            'sertifikasi_kompetensi' => 'uploads/kompetensi/dummy.pdf',
            'tanda_tangan' => 'uploads/ttd/dummy.png',

            'is_verified' => 1,
        ];
    }
}
