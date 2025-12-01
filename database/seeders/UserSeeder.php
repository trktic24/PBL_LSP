<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Gunakan Hash Facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            User::factory()->count(50)->create([
                'role_id' => $asesorRole->id_role,
            ]);
        }

        // Buat 20 User Asesi (pake factory)
        if ($asesiRole) {
            User::factory()->count(20)->create([
                'role_id' => $asesiRole->id_role,
            ]);
        }
        User::create([
            'role_id' => 1, 
            'email' => 'admin@polines.ac.id',
            'password' => Hash::make('1234') // bcrypt() juga bisa, tapi Hash lebih modern
        ]);
    }
}
