<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari role
        $adminRole = Role::where('nama_role', 'admin')->first();
        $asesorRole = Role::where('nama_role', 'asesor')->first();
        $asesiRole = Role::where('nama_role', 'asesi')->first();

        // Buat Admin
        // Buat Admin
        if ($adminRole) {
            User::firstOrCreate([
                'email' => 'admin@example.com',
            ], [
                'role_id' => $adminRole->id_role,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'google_id' => null,
                'remember_token' => Str::random(10),

            ]);
        }

        // Buat Contoh Asesor
        if ($asesorRole) {
            // Kita override role_id-nya jadi 'Asesor'
            User::factory()->count(10)->create([
                'role_id' => $asesorRole->id_role,
            ]);
            $asesorUser = User::firstOrCreate(
                ['email' => 'asesor@example.com'],
                [
                    'role_id' => $asesorRole->id_role,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'google_id' => null,
                    'remember_token' => Str::random(10),
                ]);

            // FIX: Ensure Asesor profile exists and is APPROVED
            if (!$asesorUser->asesor) {
                \App\Models\Asesor::create([
                    'id_user' => $asesorUser->id_user,
                    'nama_lengkap' => 'Asesor Demo',
                    'nomor_regis' => 'REG-001',
                    'nik' => '9876543210987654',
                    'tempat_lahir' => 'Bandung',
                    'tanggal_lahir' => '1990-01-01',
                    'jenis_kelamin' => 'Laki-laki',
                    'kebangsaan' => 'Indonesia',
                    'alamat_rumah' => 'Jl. Asesor No. 1',
                    'kode_pos' => '40111',
                    'kabupaten_kota' => 'Bandung',
                    'provinsi' => 'Jawa Barat',
                    'nomor_hp' => '089876543210',
                    'NPWP' => '12.345.678.9-000.000',
                    'nama_bank' => 'Bank ABC',
                    'norek' => '1234567890',
                    'pekerjaan' => 'Dosen',
                    'status_verifikasi' => 'approved',
                    
                    // FILE PATHS (Wajib diisi sesuai migration)
                    'ktp' => 'dummy_ktp.pdf',
                    'pas_foto' => 'dummy_foto.jpg',
                    'NPWP_foto' => 'dummy_npwp.jpg',
                    'rekening_foto' => 'dummy_rekening.jpg',
                    'CV' => 'dummy_cv.pdf',
                    'ijazah' => 'dummy_ijazah.pdf',
                    'sertifikat_asesor' => 'dummy_sertifikat.pdf',
                    'sertifikasi_kompetensi' => 'dummy_kompetensi.pdf',
                    'tanda_tangan' => 'dummy_ttd.png',
                ]);
            }
        }

        // Buat Contoh Asesi
        if ($asesiRole) {
            // Kita override role_id-nya jadi 'Asesi'
            User::factory()->count(50)->create([
                'role_id' => $asesiRole->id_role,
            ]);            
            $asesiUser = User::firstOrCreate(
            ['email' => 'asesi@example.com'],
            [
                'role_id' => $asesiRole->id_role,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'google_id' => null,
                'remember_token' => Str::random(10),

            ]);

            // FIX: Ensure Asesi profile exists for this user
            if (!$asesiUser->asesi) {
                \App\Models\Asesi::updateOrCreate(
                    ['id_user' => $asesiUser->id_user],
                    [
                    'nama_lengkap' => 'Asesi Demo',
                    'nik' => '1234567890123456',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '2000-01-01',
                    'jenis_kelamin' => 'Laki-laki',
                    'kebangsaan' => 'Indonesia',
                    'pendidikan' => 'S1',
                    'pekerjaan' => 'Mahasiswa',
                    'alamat_rumah' => 'Jl. Kebhinekaan No. 1',
                    'kode_pos' => '50275',
                    'kabupaten_kota' => 'Semarang',
                    'provinsi' => 'Jawa Tengah',
                    'nomor_hp' => '081234567890',
                    'tanda_tangan' => null,
                ]);
            }
        }

        // Buat 20 User Asesi (pake factory) - DIHAPUS KARENA SUDAH ADA DI DatabaseSeeder
        // if ($asesiRole) {
        //    \App\Models\Asesi::factory()->count(20)->create();
        // }
        User::firstOrCreate([
            'email' => 'admin@polines.ac.id',
        ], [
            'role_id' => 1,
            'password' => Hash::make('1234')
        ]);

        // Buat Superadmin (Role ID assumed based on RoleSeeder order, likely 4)
        $superadminRole = Role::where('nama_role', 'superadmin')->first();
        if ($superadminRole) {
            User::firstOrCreate([
                'email' => 'superadmin@example.com',
            ], [
                'role_id' => $superadminRole->id_role,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}