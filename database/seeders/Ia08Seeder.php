<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ia08;
use App\Models\DataSertifikasiAsesi;

class Ia08Seeder extends Seeder // <-- NAMA CLASS SUDAH BENAR!
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pastikan Anda memiliki data di tabel DataSertifikasiAsesi
        if (DataSertifikasiAsesi::count() > 0) {
            
            // Buat 10 data IA08 menggunakan Factory
            Ia08::factory(10)->create();
            
            echo "10 data IA08 berhasil dibuat.\n";
        } else {
            echo "Peringatan: Tidak ada data di tabel data_sertifikasi_asesi. Data IA08 tidak dibuat.\n";
        }
    }
}