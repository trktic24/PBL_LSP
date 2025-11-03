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
        // Buat role 'admin' dengan id_role = 1
        Role::create([
            'id_role' => 1,
            'nama_role' => 'admin' // Sesuaikan nama kolom jika beda
        ]);

        // Anda bisa tambahkan role lain di sini jika perlu
        // Role::create(['id_role' => 2, 'nama_role' => 'asesor']);
        // Role::create(['id_role' => 3, 'nama_role' => 'asesi']);
    }
}