<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StandarIndustriMapa01Seeder extends Seeder
{
    /**
     * Jalankan seed.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        // Nama tabelnya PERSIS kayak ERD
        DB::table('standar_industri_mapa01')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ini data 'manual' dari ERD lu (diambil dari kolom panjang itu)
        DB::table('standar_industri_mapa01')->insert([
            ['pilihan' => 'Standar Kompetensi: SKKNI', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Kriteria asesmen dari kurikulum pelatihan', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Spesifikasi kinerja suatu perusahaan atau industri', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Spesifikasi Produk', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Pedoman khusus', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
