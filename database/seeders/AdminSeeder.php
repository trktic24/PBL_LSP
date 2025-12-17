<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // 1. Ambil Role 'admin'
        $adminRole = Role::where('nama_role', 'admin')->first();

        // Pastikan role 'admin' ditemukan sebelum melanjutkan
        if ($adminRole) {

            // 2. Ambil semua user yang punya role 'admin'
            // (Asumsi user-user ini sudah dibuat oleh UserSeeder)
            $adminUsers = User::where('role_id', $adminRole->id_role)->get();

            // 3. Buatkan profile Admin untuk setiap user tadi
            foreach ($adminUsers as $user) {
                // Gunakan factory Admin yang sudah kita buat sebelumnya
                Admin::factory()->create([
                    // Menggunakan id_user dari objek User yang sedang di-loop
                    'id_user' => $user->id_user, 
                ]);
            }
        } else {
            // Opsional: Beri pesan jika role tidak ditemukan (misalnya, di log)
            // \Log::warning("Role 'admin' tidak ditemukan. Seeder Admin dilewati.");
        }
    }
}