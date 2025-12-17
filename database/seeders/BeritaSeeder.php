<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use Carbon\Carbon;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $beritaData = [
            [
                'judul' => 'Pentingnya Sertifikasi Kompetensi di Era Digital',
                'isi' => 'Sertifikasi kompetensi menjadi bukti pengakuan tertulis atas penguasaan kompetensi kerja pada jenis profesi tertentu. Di era digital saat ini, memiliki sertifikasi sangat membantu dalam meningkatkan daya saing di dunia kerja.',
                'gambar' => 'berita1.jpg', // Pastikan file ini ada di storage/app/public/berita nanti
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'judul' => 'LSP Polines Adakan Uji Kompetensi Periode November',
                'isi' => 'LSP Polines kembali membuka pendaftaran untuk uji kompetensi periode November 2025. Berbagai skema sertifikasi tersedia mulai dari bidang Teknologi Informasi hingga Administrasi Perkantoran.',
                'gambar' => 'berita2.jpg',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'judul' => 'Workshop Pengenalan Standar SKKNI Terbaru',
                'isi' => 'Dalam rangka meningkatkan kualitas lulusan, diadakan workshop pengenalan Standar Kompetensi Kerja Nasional Indonesia (SKKNI) terbaru yang dihadiri oleh para asesor dan praktisi industri.',
                'gambar' => 'berita3.jpg',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'judul' => 'Kerjasama Baru dengan Industri Teknologi',
                'isi' => 'Untuk memperluas jaringan Tempat Uji Kompetensi (TUK), LSP Polines menandatangani MoU dengan beberapa perusahaan teknologi terkemuka di Semarang.',
                'gambar' => 'berita4.jpg',
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'judul' => 'Tips Sukses Menghadapi Asesmen Kompetensi',
                'isi' => 'Banyak peserta merasa gugup saat menghadapi asesmen. Berikut adalah tips dan trik agar Anda bisa menjalani proses asesmen dengan lancar dan mendapatkan hasil yang kompeten.',
                'gambar' => 'berita5.jpg',
                'created_at' => Carbon::now(),
            ],
        ];

        foreach ($beritaData as $data) {
            Berita::create($data);
        }
    }
}