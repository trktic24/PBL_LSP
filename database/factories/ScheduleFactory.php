<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\Asesor;
// Model Asesi tidak diperlukan lagi di sini
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        // 1. Ambil semua ID yang SUDAH ADA di database
        //    (Pastikan seeder Anda menjalankan Tuk, Skema, Asesor SEBELUM Schedule)
        $skemaIds = Skema::pluck('id_skema')->all();
        $asesorIds = Asesor::pluck('id_asesor')->all();
        $tukIds = Tuk::pluck('id_tuk')->all();

        // 2. Logika Tanggal
        $tanggalMulai = $this->faker->dateTimeBetween('+1 week', '+2 weeks');
        $tanggalSelesai = (clone $tanggalMulai)->modify('+5 days');
        $tanggalPelaksanaan = (clone $tanggalSelesai)->modify('+1 week');

        return [
            // Relasi Foreign Key
            'id_jenis_tuk' => $this->faker->numberBetween(1, 2), // 1=Sewaktu, 2=Tempat Kerja
            
            // 3. PILIH ID SECARA ACAK (TIDAK LAGI MEMANGGIL ::factory())
            'id_tuk' => $this->faker->randomElement($tukIds),
            'id_skema' => $this->faker->randomElement($skemaIds),
            'id_asesor' => $this->faker->randomElement($asesorIds),
            // id_asesi dihapus
            
            // Isi Kolom (sesuai migrasi fiks)
            'kuota_maksimal' => $this->faker->numberBetween(20, 50),
            'kuota_minimal' => 15,
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'tanggal_pelaksanaan' => $tanggalPelaksanaan->format('Y-m-d'),
            'waktu_mulai' => $this->faker->time('H:i'),
            'Status_jadwal' => $this->faker->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
        ];
    }
}