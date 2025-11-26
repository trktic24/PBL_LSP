<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Asesi;
use App\Models\DataSertifikasiAsesi; // Model Pivot Anda
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSertifikasiAsesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua ID Jadwal dan Asesi
        $jadwalIds = Jadwal::pluck('id_jadwal');
        $asesiIds = Asesi::pluck('id_asesi');

        if ($jadwalIds->isEmpty() || $asesiIds->isEmpty()) {
            $this->command->warn('Tidak ada data Jadwal atau Asesi. Harap jalankan JadwalSeeder dan AsesiSeeder terlebih dahulu.');
            return;
        }

        // 2. Loop melalui setiap jadwal yang ada
        foreach ($jadwalIds as $jadwalId) {
            
            // Tentukan jumlah asesi yang akan didaftarkan ke jadwal ini (misal: 2 hingga 5 asesi)
            $count = rand(2, 5); 

            // Ambil ID Asesi secara acak sejumlah $count, tanpa duplikasi
            $randomAsesiIds = $asesiIds->random($count); 

            // 3. Loop melalui Asesi acak untuk membuat record di tabel pivot
            foreach ($randomAsesiIds as $asesiId) {
                
                // Gunakan Factory untuk membuat data sertifikasi yang lengkap
                DataSertifikasiAsesi::factory()->create([
                    'id_jadwal' => $jadwalId, 
                    'id_asesi' => $asesiId,
                    'tanggal_daftar' => now(), // Override tanggal_daftar
                ]);
            }
        }
        
        $this->command->info('✅ Data sertifikasi asesi (pendaftaran) berhasil ditambahkan secara dinamis.');
    }
}