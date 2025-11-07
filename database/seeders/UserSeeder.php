<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
=======
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
>>>>>>> origin/kelompok_1

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // 1. Membuat 1 user Admin (data spesifik)
        // Pola ini sama seperti Anda membuat 'Junior Web Developer'
        User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password123', // Tetap pakai teks biasa
            'role_id' => 1, // Asumsi 1 = role admin
        ]);

        // 2. Membuat 1 user Asesor (data spesifik)
        User::factory()->create([
            'username' => 'asesor01',
            'email' => 'asesor@example.com',
            'password' => 'password123',
            'role_id' => 2, // Asumsi 2 = role asesor
        ]);

        // 3. Membuat 5 user Asesi/Peserta (data acak)
        // Pola ini sama seperti Anda membuat 'count(5)'
        User::factory()->count(5)->create([
            'role_id' => 3, // Asumsi 3 = role asesi/peserta
        ]);
        
        // Catatan: Jika role_id ingin acak juga, 
        // Anda bisa mengaturnya di UserFactory.php
    }
}
=======
        // -- LANGKAH 1: Ambil Role yang udah dibuat sama RoleSeeder --

        // Pastikan nama role-nya SAMA PERSIS kayak di RoleSeeder.php
        // (admin, asesi, asesor, superadmin)
        $adminRole = Role::where('nama_role', 'admin')->first();
        $asesorRole = Role::where('nama_role', 'asesor')->first();
        $asesiRole = Role::where('nama_role', 'asesi')->first();
        $superadminRole = Role::where('nama_role', 'superadmin')->first();


        // -- LANGKAH 2: Buat Data User (Admin, Asesor, Asesi) --

        // Buat 1 User Super Admin (spesifik)
        if ($superadminRole) {
            User::factory()->create([
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => $superadminRole->id_role,
            ]);
        }

        // Buat 1 User Admin (spesifik)
        if ($adminRole) {
            User::factory()->create([
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id_role,
            ]);
        }

        // Buat 5 User Asesor (pake factory)
        if ($asesorRole) {
            // Kita override role_id-nya jadi 'Asesor'
            User::factory()->count(5)->create([
                'role_id' => $asesorRole->id_role,
            ]);
        }

        // Buat 20 User Asesi (pake factory)
        if ($asesiRole) {
            User::factory()->count(20)->create([
                'role_id' => $asesiRole->id_role,
            ]);
        }
    }
}
>>>>>>> origin/kelompok_1
