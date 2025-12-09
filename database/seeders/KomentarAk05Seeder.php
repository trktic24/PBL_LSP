<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ak05;
use App\Models\KomentarAk05;

class KomentarAk05Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua ID yang sudah ada dari tabel induk
        
        // Ambil semua ID Asesi. Jika datanya sangat banyak, pertimbangkan menggunakan chunk().
        $asesiIds = DataSertifikasiAsesi::pluck('id_data_sertifikasi_asesi')->all();
        
        // Ambil semua ID Ak05.
        $ak05Ids = Ak05::pluck('id_ak05')->all();

        // Pilihan untuk enum 'rekomendasi'
        $rekomendasiPilihan = ['K', 'BK']; 

        // 2. Lakukan perulangan bersarang (Nested Loop) untuk membuat semua kombinasi
        
        // Loop melalui setiap ID Asesi
        foreach ($asesiIds as $idAsesi) {
            
            // Untuk setiap Asesi, loop melalui setiap ID Ak05
            foreach ($ak05Ids as $idAk05) {
                
                // Gunakan KomentarAk05 Factory untuk membuat record baru
                KomentarAk05::factory()->create([
                    'id_data_sertifikasi_asesi' => $idAsesi, // ID Asesi yang sedang di-loop
                    'id_ak05' => $idAk05,                 // ID Ak05 yang sedang di-loop
                    
                    // Isi kolom lain dengan data palsu atau acak:
                    'rekomendasi' => array_rand($rekomendasiPilihan) ? 'K' : 'BK',
                    'keterangan' => $this->faker->optional(0.9)->text(500), 
                ]);
            }
        }
    }
}
