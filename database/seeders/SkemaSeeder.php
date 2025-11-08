<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema; // Pastikan Model Skema di-import

class SkemaSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // Ganti 'category' dengan nama kolom yang benar di tabel skema Anda
        $nama_kolom_kategori = 'category'; 
        
        // Hapus data lama (opsional, jika Anda ingin mulai bersih)
        // Skema::truncate(); 

        // Data Skema 1: Software
        Skema::create([
            'nama_skema' => 'Skema Sertifikasi Web Developer',
            $nama_kolom_kategori => 'Software', 
            'deskripsi_skema' => 'Sertifikasi untuk pengembang aplikasi web.',
            // ... kolom lain (harga, gambar, dll) ...
        ]);
        
        // Data Skema 2: Hardware
        Skema::create([
            'nama_skema' => 'Skema Sertifikasi Teknisi Komputer',
            $nama_kolom_kategori => 'Hardware',
            'deskripsi_skema' => 'Sertifikasi untuk perakitan dan perbaikan perangkat keras.',
            // ... kolom lain ...
        ]);
        
        // Data Skema 3: Jaringan
        Skema::create([
            'nama_skema' => 'Skema Sertifikasi Jaringan Komputer',
            $nama_kolom_kategori => 'Jaringan',
            'deskripsi_skema' => 'Sertifikasi untuk instalasi dan administrasi jaringan.',
            // ... kolom lain ...
        ]);

        // Tambahkan data skema lain di sini sesuai kebutuhan Anda
    }
}