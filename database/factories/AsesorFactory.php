<?php

namespace Database\Factories;

use App\Models\Asesor;
<<<<<<< HEAD
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
            'jenis_kelamin' => $this->faker->boolean(),
            'kebangsaan' => 'Indonesia',
            'pekerjaan' => $this->faker->jobTitle(),
=======
use App\Models\Skema; // <-- Import Skema
use App\Models\User;  // <-- Import User
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asesor>
 */
class AsesorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asesor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Kita ambil ID User dan Skema secara acak
            // Ini ASUMSI tabel user & skema udah keisi dulu
            'id_user' => User::inRandomOrder()->first()->id_user,
            'id_skema' => Skema::inRandomOrder()->first()->id_skema,

            'nomor_regis' => 'MET.' . $this->faker->unique()->numberBetween(1000000, 9999999),
            'nama_lengkap' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('################'), // 16 digit NIK
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2000-01-01'),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'kebangsaan' => 'Indonesia',
            'pekerjaan' => $this->faker->jobTitle(),

>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
            'alamat_rumah' => $this->faker->address(),
            'kode_pos' => $this->faker->postcode(),
            'kabupaten_kota' => $this->faker->city(),
            'provinsi' => $this->faker->state(),
<<<<<<< HEAD
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
            'is_verified' => $this->faker->boolean(),
        ];
    }
}
=======
            'nomor_hp' => '08' . $this->faker->numerify('##########'),
            'NPWP' => $this->faker->numerify('##.###.###.#-###.###'),

            'nama_bank' => $this->faker->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI']),
            'norek' => $this->faker->creditCardNumber(), // Formatnya mirip nomor rekening

            // Biarkan path file null, kita ga generate file-nya
            'ktp' => null,
            'pas_foto' => null,
            'NPWP_foto' => null,
            'rekening_foto' => null,
            'CV' => null,
            'ijazah' => null,
            'sertifikat_asesor' => null,
            'sertifikasi_kompetensi' => null,
            'tanda_tangan' => null,

            // 80% user asesor langsung di-verify
            'status_verifikasi' => $this->faker->boolean(80) ? 'approved' : $this->faker->randomElement(['pending', 'rejected']),
        ];
    }
}
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
