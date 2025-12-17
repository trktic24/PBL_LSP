<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PoinPotensiAk07Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        // Nama tabelnya PERSIS kayak ERD
        DB::table('poin_potensi_AK07')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ini data 'manual' dari ERD lu
        DB::table('poin_potensi_AK07')->insert([
            ['pilihan' => 'Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Pelatihan / belajar mandiri atau otodidak', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
