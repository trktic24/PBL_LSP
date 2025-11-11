<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PemenuhanDimensiAk06Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        // Nama tabel PERSIS kayak ERD
        DB::table('pemenuhan_dimensi_ak06')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ini data 5 dimensi dari ERD lu
        DB::table('pemenuhan_dimensi_ak06')->insert([
            ['nama_dimensi' => 'test skill', 'created_at' => now(), 'updated_at' => now()],
            ['nama_dimensi' => 'test management skills', 'created_at' => now(), 'updated_at' => now()],
            ['nama_dimensi' => 'contingency management skills', 'created_at' => now(), 'updated_at' => now()],
            ['nama_dimensi' => 'environment skills', 'created_at' => now(), 'updated_at' => now()],
            ['nama_dimensi' => 'transfer skills', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
