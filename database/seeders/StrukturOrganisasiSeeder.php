<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StrukturOrganisasi;

class StrukturOrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan urutan ini JANGAN DIUBAH karena View Blade memanggil berdasarkan ID 1-11
        $strukturData = [
            // 1. Ketua Dewan Pengarah
            [
                'jabatan' => 'Ketua Dewan Pengarah',
                'nama' => 'Karnowahadi, S.E., M.M.',
                'gambar' => null, 
            ],
            // 2. Ketua LSP
            [
                'jabatan' => 'Ketua LSP',
                'nama' => 'Edi Wijayanto, S.E., M.Si',
                'gambar' => null,
            ],
            // 3. Ketua Bidang Administrasi
            [
                'jabatan' => 'Ketua Bidang Administrasi',
                'nama' => 'Nurseto Adhi, S.E, M.Si',
                'gambar' => null,
            ],
            // 4. Anggota Bidang Administrasi
            [
                'jabatan' => 'Anggota Bidang Administrasi',
                'nama' => 'Khomsatul Artati, S.E. Akt., M.Si.',
                'gambar' => null,
            ],
            // 5. Ketua Bidang Sertifikasi
            [
                'jabatan' => 'Ketua Bidang Sertifikasi',
                'nama' => 'Dra. Sugiarti, M.Si',
                'gambar' => null,
            ],
            // 6. Anggota Bidang Sertifikasi
            [
                'jabatan' => 'Anggota Bidang Sertifikasi',
                'nama' => 'Ali Sai\'in, S.Pd., M.T.', // Pakai backslash sebelum petik satu
                'gambar' => null,
            ],
            // 7. Ketua Komite Skema
            [
                'jabatan' => 'Ketua Komite Skema',
                'nama' => 'Jamal Mahbub, S.T., M.T',
                'gambar' => null,
            ],
            // 8. Anggota Komite Skema
            [
                'jabatan' => 'Anggota Komite Skema',
                'nama' => 'Kuwat Santoso, S.Kom., M.Kom.',
                'gambar' => null,
            ],
            // 9. Ketua Bidang Kerjasama
            [
                'jabatan' => 'Ketua Bidang Kerjasama',
                'nama' => 'Bagus Yunianto Wibowo, S.E., S.Kom., M.M.',
                'gambar' => null,
            ],
            // 10. Ketua Bidang Manajemen Mutu
            [
                'jabatan' => 'Ketua Bidang Manajemen Mutu',
                'nama' => 'Drs. M. Asrori, M.Si',
                'gambar' => null,
            ],
            // 11. Anggota Bidang Manajemen Mutu
            [
                'jabatan' => 'Anggota Bidang Manajemen Mutu',
                'nama' => 'Tri Raharjo Yudantoro, S.Kom., M.Kom.',
                'gambar' => null,
            ],
        ];

        // Kosongkan tabel dulu agar ID reset dari 1 (Penting untuk mapping ID di View)
        StrukturOrganisasi::truncate();

        foreach ($strukturData as $data) {
            StrukturOrganisasi::create($data);
        }
    }
}