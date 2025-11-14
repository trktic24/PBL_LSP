<?php

namespace Database\Factories;

<<<<<<< HEAD
use App\Models\Jadwal;
// Import SEMUA dependensi
use App\Models\JenisTuk;
use App\Models\MasterTuk; // Model yang baru kita buat
use App\Models\Skema;
use App\Models\Asesor;    // Model yang baru kita buat
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    protected $model = Jadwal::class;

    public function definition(): array
    {
        $mulai = $this->faker->dateTimeBetween('now', '+1 month');
        $selesai = $this->faker->dateTimeBetween($mulai, (clone $mulai)->modify('+2 weeks'));
        $pelaksanaan = $this->faker->dateTimeBetween($selesai, (clone $selesai)->modify('+1 month'));

        return [
            // Panggil semua factory dependensi
            'id_jenis_tuk' => JenisTuk::factory(), // Kamu sudah punya
            'id_tuk' => MasterTuk::factory(),       // Baru kita buat
            'id_skema' => Skema::factory(),       // Kamu sudah punya
            'id_asesor' => Asesor::factory(),    // Baru kita buat

            // Isi kolom
=======
// Impor model-model yang diperlukan, termasuk yang diasumsikan
use App\Models\Asesor;
use App\Models\JenisTuk;
use App\Models\MasterTuk;
use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Logika untuk tanggal yang masuk akal
        $mulai = $this->faker->dateTimeBetween('+1 week', '+2 week');
        $selesai = (clone $mulai)->modify('+5 days');
        $pelaksanaan = (clone $selesai)->modify('+1 week');

        return [
            // Baris ini akan otomatis membuat data baru di tabel relasi
            // jika belum ada data.
            'id_jenis_tuk' => JenisTuk::inRandomOrder()->value('id_jenis_tuk'),
            'id_tuk'       => MasterTuk::inRandomOrder()->value('id_tuk'),
            'id_skema'     => Skema::inRandomOrder()->value('id_skema'),
            'id_asesor'    => Asesor::inRandomOrder()->value('id_asesor'),


            'kuota_maksimal' => $this->faker->numberBetween(50, 100),
            'kuota_minimal' => 15, // Sesuai default di migrasi
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,
<<<<<<< HEAD
            'Status_jadwal' => 'Terjadwal',
        ];
    }
}
=======
            'waktu_mulai' => $mulai->format('H:i:s'),
            'Status_jadwal' => $this->faker->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
        ];
    }
}
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
