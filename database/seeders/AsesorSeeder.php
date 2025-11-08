<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesor;
use App\Models\User;

class AsesorSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user asesor yang dibuat oleh UserSeeder
        $asesorUser = User::where('email', 'asesor@example.com')->first();

        if ($asesorUser) {
            // Isi kedua kolom supaya tidak error
            Asesor::factory()->create([
                'id_user' => $asesorUser->id_user,  // kolom nullable
                'user_id' => $asesorUser->id_user,  // kolom wajib isi (NOT NULL)
            ]);
        }
    }
}
