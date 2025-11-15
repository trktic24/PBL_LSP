<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Pastikan Anda punya model Role

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus isi yang lama dan ganti dengan ini:

        // 1. Buat role 'admin'
        Role::updateOrCreate(
            ['id_role' => 1], // Cari berdasarkan ID ini
            ['nama_role' => 'admin'] // Data untuk di-update atau di-create
        );

        // 2. Buat role 'asesor'
        Role::updateOrCreate(
            ['id_role' => 2],
            ['nama_role' => 'asesor']
        );

        // 3. Buat role 'asesi'
        Role::updateOrCreate(
            ['id_role' => 3],
            ['nama_role' => 'asesi']
        );
    }
}