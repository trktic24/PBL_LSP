<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari role
        $adminRole = Role::where('nama_role', 'admin')->first();
        $asesorRole = Role::where('nama_role', 'asesor')->first();
        $asesiRole = Role::where('nama_role', 'asesi')->first();

        // Buat Admin
        if ($adminRole) {
            User::updateOrCreate([
                'role_id' => $adminRole->id_role,
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'google_id' => null,
                'remember_token' => Str::random(10),

            ]);
        }

        // Buat Contoh Asesor
        if ($asesorRole) {
            // Kita override role_id-nya jadi 'Asesor'
            User::factory()->count(10)->create([
                'role_id' => $asesorRole->id_role,
            ]);
            User::updateOrCreate(
                ['email' => 'asesor@example.com'],
                [
                    'role_id' => $asesorRole->id_role,
                    'email' => 'asesor@example.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'google_id' => null,
                    'remember_token' => Str::random(10),
                ]);
        }

        // Buat Contoh Asesi
        if ($asesiRole) {
            // Kita override role_id-nya jadi 'Asesi'
            User::factory()->count(20)->create([
                'role_id' => $asesiRole->id_role,
            ]);            
            User::updateOrCreate(
            ['email' => 'asesi@example.com'],
            [
                'role_id' => $asesiRole->id_role,
                'email' => 'asesi@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'google_id' => null,
                'remember_token' => Str::random(10),

            ]);
        }

        // Buat 20 User Asesi (pake factory)
        if ($asesiRole) {
            User::factory()->count(20)->create([
                'role_id' => $asesiRole->id_role,
            ]);
        }
        User::create([
            'role_id' => 1,  // Ini akan berhasil karena RoleSeeder berjalan duluan
            'email' => 'admin@polines.ac.id',
            'password' => Hash::make('1234') // bcrypt() juga bisa, tapi Hash lebih modern
        ]);
    }
}