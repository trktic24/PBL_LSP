<?php

namespace Database\Seeders;

use App\Models\Asesor;
use App\Models\Role;
use App\Models\Skema; // <-- Pastikan ini di-import
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -- LANGKAH 1: Buat data master Skema --
        // (Ini ASUMSI lu udah punya SkemaFactory)
        // Kalo belum, bikin dulu: php artisan make:factory SkemaFactory
        Skema::factory()->count(10)->create();

        // Ambil SEMUA ID skema yang barusan dibuat
        $skemaIds = Skema::pluck('id_skema');


        // -- LANGKAH 2: Ambil Role Asesor --
        $asesorRole = Role::where('nama_role', 'asesor')->first();

        // Kalo role-nya ada, lanjut
        if ($asesorRole) {

            // -- LANGKAH 3: Ambil semua user yang rolenya asesor --
            $asesorUsers = User::where('role_id', $asesorRole->id_role)->get();

            // -- LANGKAH 4: Buat Data Profile (Asesor) --
            // Sekarang, kita bikin profile Asesor untuk user-user tadi
            foreach ($asesorUsers as $user) {
                // Logika baru: 50% dapet skema, 50% null
                // (rand(0, 1) == 1) artinya "kalo dapet angka 1"
                $skemaId = (rand(0, 1) == 1) ? $skemaIds->random() : null;

                Asesor::factory()->create([
                    'id_user' => $user->id_user, // <- Ini kuncinya
                    'id_skema' => $skemaId,     // <- Kita override di sini

                    // (Optional) Kalo mau namanya sama persis dgn user email
                    // 'nama_lengkap' => $user->email,
                ]);
            }
        }
    }
}
