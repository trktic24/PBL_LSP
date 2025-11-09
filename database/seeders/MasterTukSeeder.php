<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterTuk; // Menggunakan Model MasterTuk

class MasterTukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. MENGHAPUS DATA LAMA (TRUNCATE)
        // Perintah ini menghapus semua baris data di tabel master_tuk
        // dan mereset auto-increment ID.
        MasterTuk::truncate();
        
        // 2. MEMBUAT DATA BARU
        // Buat 10 data dummy lokasi TUK
        MasterTuk::factory()->count(10)->create();
    }
}