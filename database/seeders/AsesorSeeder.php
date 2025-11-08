<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asesor;
use App\Models\User; // <-- DITAMBAHKAN: Import model User

class AsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cari User asesor yang DIBUAT OLEH UserSeeder
        $asesorUser = User::where('email', 'asesor@example.com')->first();

        // 2. Jika user-nya ada...
        if ($asesorUser) {
            // 3. Buat data asesor dan SAMBUNGKAN 'id_user'-nya secara manual
            Asesor::factory()->create([
                'id_user' => $asesorUser->id_user,
            ]);
        }

        // Catatan: Baris 'Asesor::factory(10)->create();' akan gagal
        // karena tidak memiliki User. Biarkan di-comment.
    }
}