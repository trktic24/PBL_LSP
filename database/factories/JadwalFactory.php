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
        // 1. Logika Tanggal
        $mulai = $this->faker->dateTimeBetween('+1 week', '+2 week');
        $selesai = (clone $mulai)->modify('+5 days');
        $pelaksanaan = (clone $selesai)->modify('+1 week');

        // 2. Logika Jam (Waktu)
        // Kita bikin objek DateTime biar gampang dihitung selisihnya
        // Misal mulai antara jam 8 pagi sampe jam 2 siang
        $waktuMulaiObj = $this->faker->dateTimeBetween('08:00', '14:00'); 
        
        // Waktu selesai otomatis +2 jam dari waktu mulai
        $waktuSelesaiObj = (clone $waktuMulaiObj)->modify('+2 hours');

        // 3. Foreign Keys
        $id_tuk = MasterTuk::inRandomOrder()->first()->id_tuk;
        $id_skema = Skema::inRandomOrder()->first()->id_skema;
        $id_asesor = Asesor::inRandomOrder()->first()->id_asesor;
        $id_jenis_tuk = JenisTuk::inRandomOrder()->first()->id_jenis_tuk;

        return [
            'id_asesor' => $id_asesor,
            'id_skema' => $id_skema,
            'id_tuk' => $id_tuk,
            'id_jenis_tuk' => $id_jenis_tuk,

            'kuota_maksimal' => $this->faker->numberBetween(50, 100),
            'kuota_minimal' => 15,
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,
            
            // Perbaikan ada di sini:
            // Format object DateTime jadi string jam:menit (H:i)
            'waktu_mulai' => $waktuMulaiObj->format('H:i'),
            'waktu_selesai' => $waktuSelesaiObj->format('H:i'),

            'Status_jadwal' => $this->faker->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
        ];
    }
}