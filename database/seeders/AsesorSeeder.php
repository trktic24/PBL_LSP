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
                'role_id' => 2, // pastikan id 2 = role asesor
            ]);
        }

        foreach ($asesorUsers as $user) {
            Asesor::factory()->create([
                'id_user' => $user->id_user, // gunakan id_user bukan id
            ]);
        }
    }
}
