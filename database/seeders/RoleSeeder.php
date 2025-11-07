<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar role yang ingin dimasukkan ke tabel
        $roles = [
            'asesi',
            'asesor',
            'admin',
            'master asesor',
            'superadmin',
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['nama_role' => $role],
                [] // tidak ada kolom lain yang perlu diisi
            );
        }
    }
}
