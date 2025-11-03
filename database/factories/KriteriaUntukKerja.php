<?php

namespace Database\Factories;

use App\Models\Kriteria;
use App\Models\Elemen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kriteria>
 */
class KriteriaFactory extends Factory
{
    /**
     * Model yang digunakan
     */
    protected $model = Kriteria::class;

    /**
     * Definisi data dummy untuk Kriteria Unjuk Kerja
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Data kriteria unjuk kerja yang realistis dari dokumen APL.02
        $kriteriaData = [
            ['no' => '1.1', 'text' => 'Konsep data dan struktur data diidentifikasi sesuai dengan konteks permasalahan'],
            ['no' => '1.2', 'text' => 'Alternatif struktur data dibandingkan kelebihan dan kekurangannya untuk konteks permasalahan yang diselesaikan'],
            ['no' => '2.1', 'text' => 'Struktur data diimplementasikan sesuai dengan bahasa pemrograman yang akan dipergunakan'],
            ['no' => '2.2', 'text' => 'Akses terhadap data dinyatakan dalam algoritma yang efisiensi sesuai bahasa pemrograman yang akan dipakai'],
            ['no' => '1.1', 'text' => 'Rancangan user interface diidentifikasi sesuai kebutuhan'],
            ['no' => '1.2', 'text' => 'Komponen user interface dialog diidentifikasi sesuai konteks rancangan proses'],
            ['no' => '1.3', 'text' => 'Urutan dari akses komponen user interface dialog dijelaskan'],
            ['no' => '1.4', 'text' => 'Simulasi (mock-up) dari aplikasi yang akan dikembangkan dibuat'],
            ['no' => '2.1', 'text' => 'Menu program sesuai dengan rancangan program diterapkan'],
            ['no' => '2.2', 'text' => 'Penempatan user interface dialog diatur secara sekuensial'],
            ['no' => '2.3', 'text' => 'Setting aktif-pasif komponen user interface dialog disesuaikan dengan urutan alur proses'],
            ['no' => '2.4', 'text' => 'Bentuk style dari komponen user interface ditentukan'],
            ['no' => '2.5', 'text' => 'Penerapan simulasi dijadikan suatu proses yang sesungguhnya'],
            ['no' => '1.1', 'text' => 'Platform (lingkungan) yang akan digunakan untuk menjalankan tools pemrograman diidentifikasi sesuai dengan kebutuhan'],
            ['no' => '1.2', 'text' => 'Tools bahasa pemrogram dipilih sesuai dengan kebutuhaan dan lingkungan pengembangan'],
            ['no' => '2.1', 'text' => 'Tools pemrogaman ter-install sesuai dengan prosedur'],
            ['no' => '2.2', 'text' => 'Tools pemrograman bisa dijalankan di lingkungan pengembangan yang telah ditetapkan'],
            ['no' => '3.1', 'text' => 'Script (source code) sederhana dibuat sesuai tools pemrogaman yang di-install'],
            ['no' => '3.2', 'text' => 'Script dapat dijalankan dengan benar dan menghasilkan keluaran sesuai scenario yang diharapkan'],
            ['no' => '1.1', 'text' => 'Kode sumber dituliskan mengikuti coding-guidelines dan best practices'],
            ['no' => '1.2', 'text' => 'Struktur program yang sesuai dengan konsep paradigmanya dibuat'],
            ['no' => '1.3', 'text' => 'Galat/error ditangani'],
            ['no' => '2.1', 'text' => 'Efisiensi penggunaan resources oleh kode dihitung'],
            ['no' => '2.2', 'text' => 'Kemudahan interaksi selalu diimplementasikan sesuai standar yang berlaku'],
            ['no' => '1.1', 'text' => 'Tipe data yang sesuai standar ditentukan'],
            ['no' => '1.2', 'text' => 'Syntax program yang dikuasai digunakan sesuai standar'],
            ['no' => '1.3', 'text' => 'Struktur kontrol program yang dikuasai digunakan sesuai standar'],
            ['no' => '2.1', 'text' => 'Program baca tulis untuk memasukkan data dari keyboard dan menampilkan ke layar monitor termasuk variasinya sesuai standar masukan/keluaran telah dibuat'],
            ['no' => '2.2', 'text' => 'Struktur kontrol percabangan dan pengulangan dalam membuat program telah digunakan'],
            ['no' => '3.1', 'text' => 'Program dengan menggunakan prosedur dibuat sesuai aturan penulisan program'],
            ['no' => '3.2', 'text' => 'Program dengan menggunakan fungsi dibuat sesuai aturan penulisan program'],
            ['no' => '3.3', 'text' => 'Program dengan menggunakan prosedur dan fungsi secara bersamaan dibuat sesuai aturan penulisan program'],
            ['no' => '3.4', 'text' => 'Keterangan untuk setiap prosedur dan fungsi telah diberikan'],
            ['no' => '4.1', 'text' => 'Dimensi array telah ditentukan'],
            ['no' => '4.2', 'text' => 'Tipe data array telah ditentukan'],
            ['no' => '4.3', 'text' => 'Panjang array telah ditentukan'],
            ['no' => '4.4', 'text' => 'Pengurutan array telah digunakan'],
            ['no' => '5.1', 'text' => 'Program untuk menulis data dalam media penyimpan telah dibuat'],
            ['no' => '5.2', 'text' => 'Program untuk membaca data dari media penyimpan telah dibuat'],
            ['no' => '6.1', 'text' => 'Kesalahan program telah dikoreksi'],
            ['no' => '6.2', 'text' => 'Kesalahan syntax dalam program telah dibebaskan'],
            ['no' => '1.1', 'text' => 'Class unit-unit reuse (dari aplikasi lain) yang sesuai dapat diidentifikasi'],
            ['no' => '1.2', 'text' => 'Keuntungan efisiensi dari pemanfaatan komponen reuse dapat dihitung'],
            ['no' => '1.3', 'text' => 'Lisensi, Hak cipta dan hak paten tidak dilanggar dalam pemanfaatan komponen reuse tersebut'],
            ['no' => '2.1', 'text' => 'Ketergantungan antar unit diidentifikasi'],
            ['no' => '2.2', 'text' => 'Penggunaan komponen yang sudah obsolete dihindari'],
            ['no' => '2.3', 'text' => 'Program yang dihubungkan dengan library diterapkan'],
            ['no' => '3.1', 'text' => 'Cara-cara pembaharuan library atau komponen pre-existing diidentifikasi'],
            ['no' => '3.2', 'text' => 'Pembaharuan library atau komponen preexisting berhasil dilakukan'],
            ['no' => '1.1', 'text' => 'Modul program diidentifikasi'],
            ['no' => '1.2', 'text' => 'Parameter yang dipergunakan diidentifikasi'],
            ['no' => '1.3', 'text' => 'Algoritma dijelaskan cara kerjanya'],
            ['no' => '1.4', 'text' => 'Komentar setiap baris kode termasuk data, eksepsi, fungsi, prosedur dan class (bila ada) diberikan'],
            ['no' => '2.1', 'text' => 'Dokumentasi modul dibuat sesuai dengan identitas untuk memudahkan pelacakan'],
            ['no' => '2.2', 'text' => 'Identifikasi dokumentasi diterapkan'],
            ['no' => '2.3', 'text' => 'Kegunaan modul dijelaskan'],
            ['no' => '2.4', 'text' => 'Dokumen direvisi sesuai perubahan kode program'],
            ['no' => '3.1', 'text' => 'Dokumentasi fungsi, prosedur atau metod dibuat'],
            ['no' => '3.2', 'text' => 'Kemungkinan eksepsi dijelaskan'],
            ['no' => '3.3', 'text' => 'Dokumen direvisi sesuai perubahan kode program'],
            ['no' => '4.1', 'text' => 'Tools untuk generate dokumentasi diidentifikasi'],
            ['no' => '4.2', 'text' => 'Generate dokumentasi dilakukan'],
            ['no' => '1.1', 'text' => 'Kode program sesuai spesifikasi disiapkan'],
            ['no' => '1.2', 'text' => 'Debugging tools untuk melihat proses suatu modul dipersiapkan'],
            ['no' => '2.1', 'text' => 'Kode program dikompilasi sesuai bahasa pemrograman yang digunakan'],
            ['no' => '2.2', 'text' => 'Kriteria lulus build dianalisis'],
            ['no' => '2.3', 'text' => 'Kriteria eksekusi aplikasi dianalisis'],
            ['no' => '2.4', 'text' => 'Kode kesalahan dicatat'],
            ['no' => '3.1', 'text' => 'Perbaikan terhadap kesalahan kompilasi maupun build dirumuskan'],
            ['no' => '3.2', 'text' => 'Perbaikan dilakukan'],
        ];

        // Pilih random dari data kriteria yang ada
        $selectedKriteria = fake()->randomElement($kriteriaData);

        return [
            // Foreign key ke tabel elemen
            'id_elemen' => Elemen::factory(),
            
            // Nomor kriteria (format: 1.1, 1.2, 2.1, dst)
            'no_kriteria' => $selectedKriteria['no'],
            
            // Kriteria unjuk kerja
            'kriteria' => $selectedKriteria['text'],
        ];
    }

    /**
     * State untuk kriteria dengan elemen tertentu
     */
    public function forElemen(int $elemenId): static
    {
        return $this->state(fn (array $attributes) => [
            'id_elemen' => $elemenId,
        ]);
    }

    /**
     * State untuk kriteria dengan nomor tertentu (1.1, 1.2, dst)
     */
    public function withNumber(string $number): static
    {
        // Cari kriteria yang sesuai dengan nomor
        $kriteriaData = [
            ['no' => '1.1', 'text' => 'Konsep data dan struktur data diidentifikasi sesuai dengan konteks permasalahan'],
            ['no' => '1.2', 'text' => 'Alternatif struktur data dibandingkan kelebihan dan kekurangannya untuk konteks permasalahan yang diselesaikan'],
            ['no' => '2.1', 'text' => 'Struktur data diimplementasikan sesuai dengan bahasa pemrograman yang akan dipergunakan'],
            ['no' => '2.2', 'text' => 'Akses terhadap data dinyatakan dalam algoritma yang efisiensi sesuai bahasa pemrograman yang akan dipakai'],
        ];

        $kriteria = collect($kriteriaData)->firstWhere('no', $number);
        
        return $this->state(fn (array $attributes) => [
            'no_kriteria' => $kriteria['no'] ?? $number,
            'kriteria' => $kriteria['text'] ?? fake()->sentence(),
        ]);
    }
}