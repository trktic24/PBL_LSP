<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skema;
use App\Models\Asesor;
use Illuminate\Support\Facades\Hash; // <-- DI SINI TAMBAHANNYA

class AsesorSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        // 1. Buat 1 Skema "Pemrograman" (atau ambil jika sudah ada)
        $skema = Skema::firstOrCreate(
            ['nama_skema' => 'Pemrograman'],
            [
                'kode_skema' => 'SKM-001', 
                'jenis_skema' => 'KKNI',
            ]
        );

        // 2. Buat 1 User untuk "Ajeng"
        $userAjeng = User::factory()->create([
            'username' => 'ajeng.febria',
            'email' => 'ajeng@example.com',
            
            // <-- DI SINI PERBAIKANNYA
            'password' => Hash::make('password123'), // passwordnya 'password123'
            
            'role_id' => 2, // 2 = asesor
        ]);

        // 3. Buat Profil Asesor untuk "Ajeng"
        Asesor::factory()->create([
            'id_user' => $userAjeng->id_user,
            'id_skema' => $skema->id_skema,
            'nama_lengkap' => 'Ajeng Febria Hidayati',
            'nomor_regis' => '90973646526352', // Nomor dari view Anda
            'pekerjaan' => 'Dosen',
            'is_verified' => true,
            'pas_foto' => 'images/profil_asesor.jpeg', // Gunakan foto default
        ]);

        // 4. Buat 9 asesor acak lainnya (opsional)
        Asesor::factory()->count(9)->create();
    }
}