<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Asesor;

class AsesorSeeder extends Seeder
{
    public function run(): void
    {
        // ambil semua user dengan role asesor
        $asesorUsers = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'asesor');
        })->get();

        if ($asesorUsers->isEmpty()) {
            // kalau belum ada user asesor, buat beberapa dummy user
            $asesorUsers = User::factory()->count(5)->create([
                'role_id' => 3, // pastikan id 3 = role asesor
            ]);
        }

        foreach ($asesorUsers as $user) {
            Asesor::factory(10)->create([
                'id_user' => $user->id_user, // gunakan id_user bukan id
                'status_verifikasi' => 'approved', // Force approved for testing
            ]);
        }
    }
}
