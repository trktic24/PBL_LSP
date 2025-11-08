<?php

namespace Database\Factories;

use App\Models\Jadwal;
use App\Models\JenisTuk;
use App\Models\MasterTuk;
use App\Models\Skema;
use App\Models\Asesor;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    protected $model = Jadwal::class;

    public function definition(): array
    {
        $tanggalMulai = $this->faker->dateTimeBetween('now', '+1 month');
        $tanggalSelesai = (clone $tanggalMulai)->modify('+3 days');
        $tanggalPelaksanaan = (clone $tanggalSelesai)->modify('+2 days');

        return [
            'id_jenis_tuk' => JenisTuk::inRandomOrder()->first()?->id_jenis_tuk ?? JenisTuk::factory(),
            'id_tuk' => MasterTuk::inRandomOrder()->first()?->id_tuk ?? MasterTuk::factory(),
            'id_skema' => Skema::inRandomOrder()->first()?->id_skema ?? Skema::factory(),
            'id_asesor' => Asesor::inRandomOrder()->first()?->id_asesor ?? Asesor::factory(),

            'kuota_maksimal' => $this->faker->numberBetween(20, 50),
            'kuota_minimal' => $this->faker->numberBetween(10, 15),
            'sesi' => $this->faker->numberBetween(1, 5),

            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'tanggal_pelaksanaan' => $tanggalPelaksanaan,
            // 🔹 Tambahkan baris ini!
            'waktu_mulai' => $this->faker->time('H:i:s', '17:00:00'),

            'Status_jadwal' => $this->faker->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
        ];
    }
}
