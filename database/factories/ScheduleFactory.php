<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\Asesor;
use App\Models\Asesi;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        // Logika untuk membuat urutan tanggal yang masuk akal
        $tanggalMulai = $this->faker->dateTimeBetween('+1 week', '+2 weeks');
        $tanggalSelesai = (clone $tanggalMulai)->modify('+5 days');
        $tanggalPelaksanaan = (clone $tanggalSelesai)->modify('+1 week');

        return [
            // Relasi Foreign Key
            'id_jenis_tuk' => $this->faker->numberBetween(1, 2), // 1=Sewaktu, 2=Tempat Kerja
            'id_tuk' => Tuk::factory(),
            'id_skema' => Skema::factory(),
            'id_asesor' => Asesor::factory(),
            
            // === ISI KOLOM SESUAI MIGRASI FIKS ===
            'kuota_maksimal' => $this->faker->numberBetween(20, 50),
            'kuota_minimal' => 15,
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'tanggal_pelaksanaan' => $tanggalPelaksanaan->format('Y-m-d'), // Hanya tanggal
            'waktu_mulai' => $this->faker->time('H:i'), // Hanya waktu, misal: "09:00"
            'Status_jadwal' => $this->faker->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
        ];
    }
}