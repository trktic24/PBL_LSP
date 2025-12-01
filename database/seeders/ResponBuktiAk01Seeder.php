<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\BuktiAk01;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponBuktiAk01;
use Illuminate\Database\Seeder;

class ResponBuktiAk01Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Data Asesi
        $allDataSertifikasi = DataSertifikasiAsesi::pluck('id_data_sertifikasi_asesi');
        $jumlahAsesi = $allDataSertifikasi->count(); // Misal: 25

        // 2. Cek Jumlah Bukti
        $jumlahBukti = BuktiAk01::count();

        // Pastikan jumlah bukti MINIMAL sama dengan jumlah asesi (supaya cukup dibagi)
        if ($jumlahBukti < $jumlahAsesi) {
            $kurang = $jumlahAsesi - $jumlahBukti;
            $this->command->info("Menambahkan $kurang bukti baru agar pas dengan jumlah asesi...");
            
            BuktiAk01::factory()->count($kurang)->create();
        }

        // 3. Ambil ID Bukti lalu ACAK URUTANNYA (Shuffle)
        // Kita pakai Collection Laravel supaya gampang
        $allBuktiAk01 = BuktiAk01::pluck('id_bukti_ak01')->shuffle();

        $this->command->info("Memulai seeding 1-on-1 Unik ($jumlahAsesi data)...");

        // 4. LOGIKA PEMBAGIAN KARTU
        foreach ($allDataSertifikasi as $idSertifikasi) {
            
            // Ambil satu ID bukti dari tumpukan paling atas, lalu HAPUS dari tumpukan
            // Fungsi shift() atau pop() memastikan ID ini tidak akan terambil lagi
            $idBuktiUnik = $allBuktiAk01->shift(); 

            // Cek safety: Kalau bukti habis (harusnya gak mungkin kalau logika di atas bener)
            if (!$idBuktiUnik) break;

            // Cek duplikasi (biar aman kalau seeder dijalankan berulang)
            $exists = ResponBuktiAk01::where('id_data_sertifikasi_asesi', $idSertifikasi)->exists();

            if (!$exists) {
                ResponBuktiAk01::factory()->create([
                    'id_data_sertifikasi_asesi' => $idSertifikasi,
                    'id_bukti_ak01' => $idBuktiUnik, // <-- ID UNIK
                ]);
            }
        }
        
        $this->command->info('Berhasil! 25 Asesi telah dipasangkan dengan 25 Bukti berbeda.');
    }
}
