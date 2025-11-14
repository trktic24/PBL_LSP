<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. Matikan dulu 'aturan' foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Hapus data lama (biar gak numpuk kalo di-seed ulang)
        DB::table('roles')->truncate();

        // 4. Nyalakan lagi 'aturan'-nya (PENTING!)
        Schema::enableForeignKeyConstraints();

        // 3. Masukkan data baru
        DB::table('roles')->insert([
            [
                'nama_role' => 'asesi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'asesor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'master admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
