<?php

namespace Database\Factories;

use App\Models\KomentarAk05; // Ganti dengan path model yang benar jika berbeda
use App\Models\DataSertifikasiAsesi; // Model untuk id_data_sertifikasi_asesi
use App\Models\Ak05; // Model untuk id_ak05
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KomentarAk05>
 */
class KomentarAk05Factory extends Factory
{
    /**
     * Nama model yang sesuai dengan factory ini.
     *
     * @var string
     */
    protected $model = KomentarAk05::class;    

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Mendapatkan instance Faker
        $faker = $this->faker;

        // Pilihan untuk kolom rekomendasi (sesuai enum di migration)
        $rekomendasiPilihan = ['K', 'BK']; // K=Kompeten, BK=Belum Kompeten

        return [
            // id_komentar_ak05 akan otomatis diisi karena merupakan primary key dan auto-increment

            // Foreign Keys: Pastikan ada data di tabel 'data_sertifikasi_asesi' dan 'ak05'
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),
            'id_ak05' => Ak05::factory(),

            // isi kolom tabel komentar_ak05
            'rekomendasi' => $faker->randomElement($rekomendasiPilihan), // Memilih acak 'K' atau 'BK'
            'keterangan' => $faker->optional(0.9)->text(500), // 90% kemungkinan diisi, 10% null

            // created_at dan updated_at akan otomatis diisi oleh Laravel (timestamps)
        ];
    }
}
