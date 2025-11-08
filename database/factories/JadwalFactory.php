<?php

namespace Database\Factories;

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
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,
            'Status_jadwal' => 'Terjadwal',
        ];
    }
}