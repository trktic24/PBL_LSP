<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KonfirmasiOrangRelevanSeeder extends Seeder
{
    /**
     * Jalankan seed.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        // Nama tabelnya PERSIS kayak ERD
        DB::table('Konfirmasi_dengan_orang_yang_relevan')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ini data 'manual' dari ERD lu
        DB::table('Konfirmasi_dengan_orang_yang_relevan')->insert([
            ['pilihan' => 'Manajer sertifikasi LSP', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Master Assessor / Master Trainer / Asesor Utama kompetensi', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
