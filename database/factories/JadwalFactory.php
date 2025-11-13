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
        // Logika untuk tanggal yang masuk akal
        $mulai = $this->faker->dateTimeBetween('+1 week', '+2 week');
        $selesai = (clone $mulai)->modify('+5 days');
        $pelaksanaan = (clone $selesai)->modify('+1 week');
        $id_tuk = MasterTuk::inRandomOrder()->first()->id_tuk;
        $id_skema = Skema::inRandomOrder()->first()->id_skema; // Asumsi SkemaFactory ada
        $id_asesor = Asesor::inRandomOrder()->first()->id_asesor; // Asumsi AsesorFactory ada
        $id_jenis_tuk = JenisTuk::inRandomOrder()->first()->id_jenis_tuk;


        return [
            // Baris ini akan otomatis membuat data baru di tabel relasi
            // jika belum ada data.
            // Ganti 'Asesor::factory()' dengan variabel random tadi
            'id_asesor' => $id_asesor, 
            
            // Lakukan hal yang sama untuk foreign key lainnya
            'id_skema' => $id_skema,
            'id_tuk' => $id_tuk,
            'id_jenis_tuk' => $id_jenis_tuk,

            'kuota_maksimal' => $this->faker->numberBetween(50, 100),
            'kuota_minimal' => 15, // Sesuai default di migrasi
            'sesi' => $this->faker->numberBetween(1, 3),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,
            'waktu_mulai' => $this->faker->time('H:i'),
            'Status_jadwal' => $this->faker->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
        ];
    }
}
