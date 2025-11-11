<?php

namespace Database\Factories;

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
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,
            'waktu_mulai' => $mulai->format('H:i:s'),
            'Status_jadwal' => $this->faker->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
        ];
    }
}
