<?php

namespace Database\Factories;

use App\Models\Asesor;
use App\Models\User;  // Dependensi dari migrasi
use App\Models\Skema; // Dependensi dari migrasi
use Illuminate\Database\Eloquent\Factories\Factory;

class AsesorFactory extends Factory
{
    protected $model = Asesor::class;

    public function definition(): array
    {
        return [
            'id_skema' => Skema::factory(), // Kamu sudah punya factory ini
            'id_user' => User::factory(),  // Ini factory bawaan Laravel
            'nomor_regis' => 'MET.' . $this->faker->unique()->numerify('#########'),
            'nama_lengkap' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('################'),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'jenis_kelamin' => 'Laki-laki',
            'kebangsaan' => 'Indonesia',
            'pekerjaan' => $this->faker->jobTitle(),
            'alamat_rumah' => $this->faker->address(),
            'kode_pos' => $this->faker->postcode(),
            'kabupaten_kota' => $this->faker->city(),
            'provinsi' => $this->faker->state(),
            'nomor_hp' => $this->faker->numerify('08##########'),
            'NPWP' => $this->faker->numerify('################'),
            'nama_bank' => $this->faker->randomElement(['BCA', 'Mandiri', 'BNI']),
            'norek' => $this->faker->numerify('##########'),
            'ktp' => 'path/to/fake_ktp.jpg',
            'pas_foto' => 'path/to/fake_foto.jpg',
            'NPWP_foto' => 'path/to/fake_npwp.jpg',
            'rekening_foto' => 'path/to/fake_rekening.jpg',
            'CV' => 'path/to/fake_cv.pdf',
            'ijazah' => 'path/to/fake_ijazah.pdf',
            'sertifikat_asesor' => 'path/to/fake_sert_asesor.pdf',
            'sertifikasi_kompetensi' => 'path/to/fake_sert_kompetensi.pdf',
            'tanda_tangan' => 'path/to/fake_ttd.png',
            // 'is_verified' => $this->faker->boolean(),
        ];
    }
}
