<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Asesi;
use App\Models\DataSertifikasiAsesi;

class DataSertifikasiAsesiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil semua Jadwal dan Asesi yang sudah ada
        $schedules = Schedule::all();
        $asesis = Asesi::all();

        // Jika data kosong, skip (hindari error)
        if ($schedules->isEmpty() || $asesis->isEmpty()) {
            return;
        }

        // 2. Loop setiap Jadwal
        foreach ($schedules as $schedule) {
            // Ambil sejumlah Asesi secara acak (misal 5 sampai 15 orang per jadwal)
            // Kita pakai take() supaya tidak mengambil semua asesi jika datanya banyak
            $pesertaRandom = $asesis->random(rand(5, 15));

            foreach ($pesertaRandom as $peserta) {
                // 3. Buat data pendaftaran menggunakan Factory
                DataSertifikasiAsesi::factory()->create([
                    'id_jadwal' => $schedule->id_jadwal,
                    'id_asesi' => $peserta->id_asesi,
                    // Tanggal daftar disamakan dengan tanggal mulai pendaftaran jadwal
                    'tanggal_daftar' => $schedule->tanggal_mulai, 
                ]);
            }
        }
    }
}