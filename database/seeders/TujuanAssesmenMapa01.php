<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TujuanAssesmenMapa01 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('master_tujuan_sertifikasi')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ini data 'manual' lu
        DB::table('master_tujuan_sertifikasi')->insert([
            ['nama_tujuan' => 'Sertifikasi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tujuan' => 'Pengakuan Kompetensi Terkini (PKT)', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tujuan' => 'Rekognisi Pembelajaran Lampau (RPL)', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tujuan' => 'Lainnya', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
