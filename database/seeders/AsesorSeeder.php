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
        // --- PERBAIKAN #2: Ambil Role 'asesor' DULU ---
        $asesorRole = Role::where('nama_role', 'asesor')->first();

        // --- PERBAIKAN #3: Cek dulu apakah role-nya ada ---
        if (!$asesorRole) {
            // Beri peringatan di console dan hentikan seeder ini
            $this->command->warn('Role "asesor" tidak ditemukan. AsesorSeeder dilewati.');
            return; // Hentikan eksekusi
        }

        // Ambil semua ID skema yang ada
        $skemaIds = Skema::pluck('id_skema');

        // Cek jika tidak ada skema sama sekali
        if ($skemaIds->isEmpty()) {
            // Buat 5 skema baru sebagai data awal
            // (Asumsi kamu punya SkemaFactory)
            $this->command->info('Tidak ada skema. Membuat 5 skema baru...');
            Skema::factory(5)->create();
            // Ambil lagi ID skema yang baru dibuat
            $skemaIds = Skema::pluck('id_skema');
        }

        // --- PERBAIKAN #4: Ambil user SETELAH role-nya pasti ada ---
        $asesorUsers = User::where('role_id', $asesorRole->id_role)->get();
        
        // Cek jika tidak ada user asesor
        if ($asesorUsers->isEmpty()) {
             $this->command->warn('Tidak ada User dengan role "asesor". Tidak ada profil asesor yang dibuat.');
             return;
        }

        $this->command->info('Membuat profil Asesor untuk ' . $asesorUsers->count() . ' user...');

        // Loop semua user asesor
        foreach ($asesorUsers as $user) {
            
            // 1. BUAT PROFIL ASESOR
            // (Asumsi kamu punya AsesorFactory yang sudah benar)
            $asesor = Asesor::factory()->create([
                'user_id' => $user->id_user,
                // Pastikan AsesorFactory-mu mengisi kolom wajib lainnya!
                // (cth: nik, tempat_lahir, dll)
            ]);

            // 2. HUBUNGKAN ASESOR KE SKEMA (ISI TABEL PIVOT)
            $randomSkemaId = $skemaIds->random();
            
            // Panggil fungsi relasi (jamak) yang sudah kita buat
            // Ini akan mengisi tabel 'transaksi_asesor_skema'
            $asesor->skema()->attach($randomSkemaId); 
            
            // Jika kamu ingin 1 asesor punya 2 skema (misalnya):
            // $randomSkemaIds = $skemaIds->random(2);
            // $asesor->skemas()->attach($randomSkemaIds);
        }
        
        $this->command->info('Profil Asesor dan relasi skema berhasil dibuat.');
    }
}