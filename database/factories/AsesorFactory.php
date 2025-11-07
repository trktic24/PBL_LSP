<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Asesor;
use App\Models\User;
use App\Models\Skema;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asesor>
 */
class AsesorFactory extends Factory
{
    /**
     * Tentukan model yang terhubung dengan factory ini.
     *
     * @var string
     */
    protected $model = Asesor::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Buat jenis kelamin acak (1=Laki, 0=Perempuan)
        $jenis_kelamin = fake()->randomElement(['Laki-laki', 'Perempuan']);
        // Buat nama yang sesuai dengan jenis kelamin
        $nama_lengkap = fake()->name($jenis_kelamin == 'Laki-laki' ? 'male' : 'female');

        return [
            // --- Foreign Keys ---
            // Baris ini akan OTOMATIS membuat User & Skema baru 
            // setiap kali AsesorFactory dipanggil.
            'id_user' => User::factory(),
            'id_skema' => \App\Models\Skema::factory(),

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