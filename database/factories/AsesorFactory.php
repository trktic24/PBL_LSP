<?php

namespace Database\Factories;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Asesor;
use App\Models\User;
use App\Models\Skema;
=======
use App\Models\Asesor;
use App\Models\Skema; // <-- Import Skema
use App\Models\User;  // <-- Import User
use Illuminate\Database\Eloquent\Factories\Factory;
>>>>>>> origin/kelompok_1

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asesor>
 */
class AsesorFactory extends Factory
{
    /**
<<<<<<< HEAD
     * Tentukan model yang terhubung dengan factory ini.
=======
     * The name of the factory's corresponding model.
>>>>>>> origin/kelompok_1
     *
     * @var string
     */
    protected $model = Asesor::class;

    /**
<<<<<<< HEAD
     * Definisikan status default model.
=======
     * Define the model's default state.
>>>>>>> origin/kelompok_1
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
<<<<<<< HEAD
        // Buat jenis kelamin acak (1=Laki, 0=Perempuan)
        $jenis_kelamin = fake()->randomElement([0, 1]);
        // Buat nama yang sesuai dengan jenis kelamin
        $nama_lengkap = fake()->name($jenis_kelamin == 1 ? 'male' : 'female');

        return [
            // --- Foreign Keys ---
            // Baris ini akan OTOMATIS membuat User & Skema baru 
            // setiap kali AsesorFactory dipanggil.
            'id_user' => User::factory(),
            'id_skema' => Skema::factory(),

            // --- Data Pribadi Asesor ---
            'nomor_regis' => fake()->unique()->numerify('REG.ASESOR.######'),
            'nama_lengkap' => $nama_lengkap,
            'nik' => fake()->unique()->numerify('################'), // 16 digit NIK
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date('Y-m-d', '2000-01-01'),
            'jenis_kelamin' => $jenis_kelamin,
            'kebangsaan' => 'Indonesia',
            'pekerjaan' => fake()->jobTitle(),

            // --- Alamat dan Kontak ---
            'alamat_rumah' => fake()->address(),
            'kode_pos' => fake()->postcode(),
            'kabupaten_kota' => fake()->city(),
            'provinsi' => fake()->state(),
            'nomor_hp' => fake()->numerify('081#########'),
            'NPWP' => fake()->numerify('##.###.###.#-###.###'), // Format NPWP

            // --- Informasi Bank ---
            'nama_bank' => fake()->randomElement(['Bank BCA', 'Bank Mandiri', 'Bank BNI', 'Bank BRI']),
            'norek' => fake()->numerify('##########'),

            // --- Path File (Hanya path dummy) ---
            'ktp' => '/storage/files/dummy_ktp.jpg',
            'pas_foto' => fake()->imageUrl(480, 640, 'people'), // URL gambar palsu
            'NPWP_foto' => '/storage/files/dummy_npwp.jpg',
            'rekening_foto' => '/storage/files/dummy_rekening.jpg',
            'CV' => '/storage/files/dummy_cv.pdf',
            'ijazah' => '/storage/files/dummy_ijazah.pdf',
            'sertifikat_asesor' => '/storage/files/dummy_sertifikat_asesor.pdf',
            'sertifikasi_kompetensi' => '/storage/files/dummy_sertifikasi_kompetensi.pdf',
            'tanda_tangan' => '/storage/files/dummy_ttd.png',

            // --- Status ---
            'is_verified' => fake()->boolean(80), // 80% kemungkinan datanya true (verified)
        ];
    }
}
=======
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

            'alamat_rumah' => $this->faker->address(),
            'kode_pos' => $this->faker->postcode(),
            'kabupaten_kota' => $this->faker->city(),
            'provinsi' => $this->faker->state(),
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
            'is_verified' => $this->faker->boolean(80),
        ];
    }
}
>>>>>>> origin/kelompok_1
