<?php

namespace Database\Seeders;

use App\Models\Asesi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil Role 'asesi'
        $asesiRole = Role::where('nama_role', 'asesi')->first();

        if ($asesiRole) {

            // 2. Ambil semua user yang punya role 'asesi'
            // (Ini adalah user yg dibuat oleh UserSeeder)
            $asesiUsers = User::where('role_id', $asesiRole->id_role)->get();

            // 3. Buatkan profile Asesi untuk setiap user tadi
            foreach ($asesiUsers as $user) {
                Asesi::factory(2)->create([
                    'id_user' => $user->id_user, // <- Ini kuncinya
                ]);
            }
        }
    }
}
