<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Asesor; // Pastikan model Asesor di-use
use App\Models\User;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua Asesor (Profile Asesor)
        // Pastikan UserSeeder sudah jalan duluan
        $allAsesor = Asesor::all();

        if ($allAsesor->count() == 0) {
            $this->command->error('Tidak ada data Asesor. Jalankan UserSeeder dulu.');
            return;
        }

        // 2. Loop setiap Asesor
        foreach ($allAsesor as $asesor) {
            
            // 3. Buatkan TEPAT 6 Jadwal untuk Asesor ini
            Jadwal::factory()->count(6)->create([
                'id_asesor' => $asesor->id_asesor, // Paksa ID Asesor ini
            ]);
            
        }

        $totalJadwal = $allAsesor->count() * 6;
        $this->command->info("Berhasil membuat {$totalJadwal} Jadwal. Setiap Asesor punya 6 Jadwal.");
    }
}