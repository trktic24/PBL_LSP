<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrAk07MasterSeeder extends Seeder
{
    public function run(): void
    {
        // A. INPUT DATA POIN POTENSI (Bagian A) [cite: 17, 18, 19]
        $potensi = [
            ['deskripsi_potensi' => 'Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi'],
            ['deskripsi_potensi' => 'Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.'],
            ['deskripsi_potensi' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi'],
            ['deskripsi_potensi' => 'Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.'],
            ['deskripsi_potensi' => 'Pelatihan / belajar mandiri atau otodidak.'],
        ];
        DB::table('poin_potensi_AK07')->insert($potensi);

        // B. INPUT DATA SOAL & OPSI (Bagian B) [cite: 20, 21]
        
        // Soal 1
        $idQ1 = DB::table('persyaratan_modifikasi_AK07')->insertGetId(['pertanyaan_karakteristik' => 'Keterbatasan bahasa, literasi, numerasi.']);
        DB::table('catatan_keterangan_AK07')->insert([
            ['id_persyaratan_modifikasi_AK07' => $idQ1, 'isi_opsi' => 'Memerlukan dukungan   pembaca, penerjemah, pelayan, penulis. untuk merekam jawaban asesi.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ1, 'isi_opsi' => 'Melakukan asesmen verbal dengan dilengkapi gambar diagram dan bentuk-benbentuk visual.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ1, 'isi_opsi' => 'Menggunakan Hasil produksi.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ1, 'isi_opsi' => 'Mengunakan Ceklis observasi/demonstrasi.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ1, 'isi_opsi' => 'Menggunakan   daftar instruksi terstruktur.'],
            
        ]);

        // Soal 2
        $idQ2 = DB::table('persyaratan_modifikasi_AK07')->insertGetId(['pertanyaan_karakteristik' => 'Penyediaan   dukungan   pembaca,   penerjemah, pelayan, penulis.']);
        DB::table('catatan_keterangan_AK07')->insert([
            ['id_persyaratan_modifikasi_AK07' => $idQ2, 'isi_opsi' => 'Menggunakan  pertanyaan lisan dengan dilengkapi gambar diagram dan bentuk-bentuk visual.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ2, 'isi_opsi' => 'Menggunakan  pertanyaan wawancara dengan dilengkapi gambar diagram dan bentuk-bentuk visual.'],
            
        ]);

        // Soal 3
        $idQ3 = DB::table('persyaratan_modifikasi_AK07')->insertGetId(['pertanyaan_karakteristik' => 'Penggunaan teknologi adaptif atau peralatan khusus. (misal: komputer, printer, digital dsb).']);
        DB::table('catatan_keterangan_AK07')->insert([
            ['id_persyaratan_modifikasi_AK07' => $idQ3, 'isi_opsi' => 'Ceklis observasi/demonstrasi Demonstrasi.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ3, 'isi_opsi' => 'Pertanyaan lisan.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ3, 'isi_opsi' => 'Pertanyaan tertulis.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ3, 'isi_opsi' => 'Pertanyaan wawancara.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ3, 'isi_opsi' => 'Daftar instruksi terstruktur.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ3, 'isi_opsi' => 'Ceklis verifikasi portofolio.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ3, 'isi_opsi' => 'Menggunakan dukungan operator komputer.'],
        ]);
        
        // Soal 4
        $idQ4 = DB::table('persyaratan_modifikasi_AK07')->insertGetId(['pertanyaan_karakteristik' => 'Pelaksanaan  asesmen   secara  fleksibel  karena alasan keletihan atau keperluan pengobatan.']);
        DB::table('catatan_keterangan_AK07')->insert([
            ['id_persyaratan_modifikasi_AK07' => $idQ4, 'isi_opsi' => 'Menggunakan juru tulis.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ4, 'isi_opsi' => 'Menggunakan kamaramen perekam vidio/ataudio.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ4, 'isi_opsi' => 'Memperbolehkan periode waktu yang lebih panjang untuk menyelesaikan tugas pekrejaan dalam asesmen.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ4, 'isi_opsi' => 'Melakukan tugas pekerjaan dalam asesmen dengan waktu lebih pendek.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ4, 'isi_opsi' => 'Menggunakan instruksi-instruksi spesifik pada proyek yang dapat dilakukan pada berbagai tingkatan.'],

        ]);

        // Soal 5
        $idQ5 = DB::table('persyaratan_modifikasi_AK07')->insertGetId(['pertanyaan_karakteristik' => 'Penyediaan  peralatan   asesmen  berupa  braille, audio/video-tape.']);
        DB::table('catatan_keterangan_AK07')->insert([
            ['id_persyaratan_modifikasi_AK07' => $idQ5, 'isi_opsi' => 'Menggunakan pertanyaan lisan.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ5, 'isi_opsi' => 'Menggunakan pertanyaan wawancara.'],
            
        ]);

        // Soal 6
        $idQ6 = DB::table('persyaratan_modifikasi_AK07')->insertGetId(['pertanyaan_karakteristik' => 'Penyesuaian tempat fisik/lingkungan asesmen']);
        DB::table('catatan_keterangan_AK07')->insert([
            ['id_persyaratan_modifikasi_AK07' => $idQ6, 'isi_opsi' => 'Pertanyaan lisan.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ6, 'isi_opsi' => 'Pertanyaan tertulis.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ6, 'isi_opsi' => 'Pertanyaan wawancara.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ6, 'isi_opsi' => 'Ceklis Verifikasi portofolio.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ6, 'isi_opsi' => 'Ceklis reviu produk.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ6, 'isi_opsi' => 'Daftar instruksi terstruktur.'],
        ]);

        // Soal 7
        $idQ7 = DB::table('persyaratan_modifikasi_AK07')->insertGetId(['pertanyaan_karakteristik' => 'Pertimbangan umur/usia lanjut/gender asesi.
(Adanya perbedaan usia dengan asesor yang lebih muda).']);
        DB::table('catatan_keterangan_AK07')->insert([
            ['id_persyaratan_modifikasi_AK07' => $idQ7, 'isi_opsi' => 'Menggunakan studi kasus/daftar instruksi terstrukut.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ7, 'isi_opsi' => 'Menggunakan instrumen asesmen dengan huruf normal jangan terlalu kecil.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ7, 'isi_opsi' => 'Menggunakan asesor dengan jenis kelamin yang sama dengan asesi'],
            ['id_persyaratan_modifikasi_AK07' => $idQ7, 'isi_opsi' => 'Menggunakan instrumen asesmen yang sama walaupun berbeda jenis kelamain.'],
        ]);

        // Soal 8
        $idQ8 = DB::table('persyaratan_modifikasi_AK07')->insertGetId(['pertanyaan_karakteristik' => 'Pertimbangan budaya/tradisi/agama.']);
        DB::table('catatan_keterangan_AK07')->insert([
            ['id_persyaratan_modifikasi_AK07' => $idQ8, 'isi_opsi' => 'Menggunakan studi kasus daftar instruksi terstrukut.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ8, 'isi_opsi' => 'Menggunakan asesor tanpa  pertimbangan budaya/tradisi/agama.'],
            ['id_persyaratan_modifikasi_AK07' => $idQ8, 'isi_opsi' => 'Menggunakan instrumen asesmen yang sama walaupun berbeda budaya/tradisi/agama).'],
        ]);

    }
}