<?php

namespace Database\Factories;

use Carbon\Carbon;
// Import SEMUA dependensi
use App\Models\Skema;
use App\Models\Jadwal;
use App\Models\JenisTuk;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Asesor;    // Model yang baru kita buat
use App\Models\MasterTuk; // Model yang baru kita buat

class JadwalFactory extends Factory
{
    protected $model = Jadwal::class;

    public function definition(): array
    {
        $hour = $this->faker->numberBetween(8, 14); 
        $minute = $this->faker->numberBetween(0, 59);
        $mulai = $this->faker->dateTimeBetween('now', '+1 month');
        $selesai = $this->faker->dateTimeBetween($mulai, (clone $mulai)->modify('+2 weeks'));
        $pelaksanaan = $this->faker->dateTimeBetween($selesai, (clone $selesai)->modify('+1 month'));
        $waktuMulai = Carbon::createFromTime($hour, $minute, 0);
        
        // Bikin Waktu Selesai = Waktu Mulai + (1 sampai 2 jam) + (0 sampai 30 menit acak)
        // Pakai clone() biar $waktuMulai aslinya gak berubah
        $waktuSelesai = (clone $waktuMulai)
                        ->addHours($this->faker->numberBetween(1, 2))
                        ->addMinutes($this->faker->numberBetween(0, 30));

        return [
            // Panggil semua factory dependensi
            'id_jenis_tuk' => JenisTuk::inRandomOrder()->first()->id_jenis_tuk, // Kamu sudah punya
            'id_tuk' => MasterTuk::factory(),       // Baru kita buat
            'id_skema' => Skema::factory(),       // Kamu sudah punya
            'id_asesor' => Asesor::factory(),    // Baru kita buat

            // Isi kolom
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,
            'Status_jadwal' => 'Terjadwal',
            'kuota_maksimal' => 40,
            'waktu_mulai' => $waktuMulai->format('H:i:s'),
            'waktu_selesai' => $waktuSelesai->format('H:i:s'),
        ];
    }
}
