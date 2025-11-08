<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\Asesor;
use App\Models\Asesi; // <-- 1. Tambahkan use Asesi
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            // Relasi Foreign Key
            'id_jenis_tuk' => $this->faker->numberBetween(1, 2),
            'id_tuk' => Tuk::factory(),
            'id_skema' => Skema::factory(),
            'id_asesor' => Asesor::factory(),
            'id_asesi' => Asesi::factory(), // <-- 2. Tambahkan Asesi
            
            // Isi Kolom (sesi, tanggal_mulai, tanggal_selesai Dihapus)
            'tanggal_pelaksanaan' => $this->faker->dateTimeBetween('+1 week', '+3 weeks'),
            'Status_jadwal' => 'Terjadwal',
        ];
    }
}