<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\MasterTuk;
use App\Models\Jadwal; // <-- Tambahkan Model Jadwal

class MasterTukSeeder extends Seeder
{

    public function run(): void
    {
        // 1. Hapus data di TABEL ANAK (Jadwal) yang merujuk ke master_tuk
        //    Ini harus dilakukan sebelum menghapus data induk.
        Jadwal::truncate(); // <-- Tambahkan baris ini!

        // 2. MENGHAPUS DATA LAMA di TABEL INDUK (MasterTuk)
        MasterTuk::truncate();
        
        // 3. MEMBUAT DATA BARU
        MasterTuk::factory()->count(10)->create();
    }
}