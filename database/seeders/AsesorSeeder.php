<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Asesor;

class AsesorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil user dengan role asesor, TAPI dibatasin cuma ambil 10 orang aja
        $asesorUsers = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'asesor');
        })->take(10)->get(); // <-- Pakai take(10) biar gak ngambil kelebihan

        // 2. Loop user-nya
        foreach ($asesorUsers as $user) {
            
            // 3. PENTING: Cek dulu, user ini udah punya data asesor belum?
            // Kan di kodingan temen lu udah ada yang dibikinin manual (si asesor@example.com)
            if (!$user->asesor) {
                
                // 4. Kalau belum punya, baru kita bikinin.
                // HAPUS angka 10 di dalam factory(). Cukup 1 user = 1 asesor.
                Asesor::factory()->create([
                    'id_user' => $user->id_user,
                ]);
            }
        }
    }
}