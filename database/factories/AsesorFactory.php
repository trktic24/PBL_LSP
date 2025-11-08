<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Skema;
use App\Models\User; // Pastikan User di-import

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asesor>
 */
class AsesorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // DIPERBAIKI: Menghapus 'user_id' dan 'id_user' duplikat.
        // Seeder (AsesorSeeder) akan menyediakan 'id_user' yang benar.
        // Kita juga hanya perlu satu 'id_skema'.

        return [
            'id_skema' => Skema::factory(), // Biarkan factory Skema membuat skema baru
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
            'nomor_hp' => fake()->phoneNumber(),
            'NPWP' => fake()->numerify('##.###.###.#-###.###'),
            'nama_bank' => fake()->randomElement(['BNI', 'BRI', 'BCA', 'Mandiri']),
            'norek' => fake()->creditCardNumber(),

            // Path file dummy (asumsi file akan di-upload nanti)
            'ktp' => null,
            'pas_foto' => null,
            'NPWP_foto' => null,
            'rekening_foto' => null,
            'CV' => null,
            'ijazah' => null,
            'sertifikat_asesor' => null,
            'sertifikasi_kompetensi' => null,
            'tanda_tangan' => null,

            'is_verified' => true, // Set default terverifikasi untuk testing
        ];
    }
}