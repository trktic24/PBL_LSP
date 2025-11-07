<?php

namespace Database\Factories;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Jadwal;
use App\Models\JenisTuk;
use App\Models\Tuk;
use App\Models\Skema;
use App\Models\Asesor;
=======
// Impor model-model yang diperlukan, termasuk yang diasumsikan
use App\Models\Asesor;
use App\Models\JenisTuk;
use App\Models\MasterTuk;
use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;
>>>>>>> origin/kelompok_1

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
<<<<<<< HEAD
     * Tentukan model yang terhubung dengan factory ini.
     *
     * @var string
     */
    protected $model = Jadwal::class;

    /**
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
        // Logika untuk membuat urutan tanggal yang masuk akal
        // 1. Tentukan tanggal mulai pendaftaran (misal: 1-2 minggu dari sekarang)
        $mulai = fake()->dateTimeBetween('+1 week', '+2 weeks');
        
        // 2. Tentukan tanggal selesai pendaftaran (misal: 5-10 hari setelah pendaftaran dibuka)
        $selesai = fake()->dateTimeBetween($mulai, (clone $mulai)->modify('+10 days'));
        
        // 3. Tentukan tanggal pelaksanaan (misal: 1-7 hari setelah pendaftaran ditutup)
        $pelaksanaan = fake()->dateTimeBetween((clone $selesai)->modify('+1 day'), (clone $selesai)->modify('+1 week'));

        return [
            // --- Foreign Keys ---
            // Otomatis membuat model baru untuk setiap relasi
            'id_jenis_tuk' => JenisTuk::factory(),
            'id_tuk' => Tuk::factory(),
            'id_skema' => Skema::factory(),
            'id_asesor' => Asesor::factory(),

            // --- Isi Kolom ---
            'sesi' => fake()->numberBetween(1, 3), // Asumsi ada 1-3 sesi
            
            // --- Tanggal (sesuai urutan) ---
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,

            // --- Status ---
            'Status_jadwal' => fake()->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
=======
        // Logika untuk tanggal yang masuk akal
        $mulai = $this->faker->dateTimeBetween('+1 week', '+2 week');
        $selesai = (clone $mulai)->modify('+5 days');
        $pelaksanaan = (clone $selesai)->modify('+1 week');

        return [
            // Baris ini akan otomatis membuat data baru di tabel relasi
            // jika belum ada data.
            'id_jenis_tuk' => JenisTuk::factory(),
            'id_tuk' => MasterTuk::factory(),
            'id_skema' => Skema::factory(), // Asumsi SkemaFactory ada
            'id_asesor' => Asesor::factory(), // Asumsi AsesorFactory ada

            'kuota_maksimal' => $this->faker->numberBetween(50, 100),
            'kuota_minimal' => 15, // Sesuai default di migrasi
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,
            'Status_jadwal' => $this->faker->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
>>>>>>> origin/kelompok_1
        ];
    }
}