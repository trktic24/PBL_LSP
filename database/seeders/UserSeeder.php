<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
