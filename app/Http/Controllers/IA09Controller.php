<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\IA09; // Di dunia nyata, Anda akan menggunakan Model

class IA09Controller extends Controller
{
    /**
     * Data Dasar (Template Kosong) yang akan dilihat Asesor saat baru mengisi.
     * Di dunia nyata, ini diambil dari record database yang baru dibuat.
     */
    private function getTemplateIA09() 
    {
        return [
            'skema' => [
                'judul' => 'Sertifikasi Operator Komputer Madya',
                'nomor' => 'OKM-01',
            ],
            'info_umum' => [
                'tuk_type' => 'Mandiri',
                'nama_asesor' => 'Dr. Rina Kusuma, M.Kom.',
                'no_reg_met' => 'MET-12345',
                'nama_asesi' => 'Bambang Sudiro',
                'tanggal' => date('Y-m-d'),
                'asesi_ttd_tgl' => '', // KOSONG di awal
                'asesi_ttd_file' => '', // KOSONG di awal
                'asesor_ttd_tgl' => '', // KOSONG di awal
                'asesor_ttd_file' => '', // KOSONG di awal
            ],
            'panduan_asesor' => [
                'Pertanyaan wawancara dapat dilakukan untuk keseluruhan unit kompetensi dalam skema sertifikasi atau dilakukan untuk masing-masing kelompok pekerjaan dalam satu skema sertifikasi.',
                'Isilah bukti portofolio sesuai dengan bukti yang diminta pada skema sertifikasi sebagaimana yang telah dibuat pada FR-IA.08.',
                'Ajukan pertanyaan verifikasi portofolio untuk semua unit/elemen kompetensi yang di checklist pada FR-IA.08',
                'Ajukan pertanyaan kepada asesi sebagai tindak lanjut hasil verifikasi portofolio.',
                'Jika hasil verifikasi potofolio telah memenuhi aturan bukti maka pertanyaan wawancara tidak perlu dilakukan terhadap bukti tersebut.',
                'Tuliskan pencapaian atas setiap kesimpulan pertanyaan wawancara dengan cara mencentang ( ) "Ya" atau "Tidak".',
            ],
            'unit_kompetensi' => [
                ['kelompok' => 'Pengolahan Data', 'kode' => 'TIK.OK02.001.01', 'judul' => 'Mengolah Data dengan Aplikasi Spreadsheet'],
                ['kelompok' => 'Presentasi', 'kode' => 'TIK.OK02.002.01', 'judul' => 'Membuat Materi Presentasi'],
            ],
            'bukti_portofolio' => [
                '1. Laporan hasil pengolahan data spreadsheet',
                '2. File presentasi (.pptx)',
                '3. Sertifikat pelatihan terkait',
                '4. Surat Keterangan Pengalaman Kerja',
            ],
            // Pertanyaan dengan isian KOSONG (Asesor belum mengisi)
            'pertanyaan' => [
                [
                    'no' => 1,
                    'pertanyaan' => 'Sesuai dengan bukti no. 1, jelaskan langkah-langkah Anda dalam memvalidasi data yang dimasukkan ke spreadsheet.',
                    'jawaban' => '', // KOSONG
                    'pencapaian' => '', // KOSONG
                ],
                [
                    'no' => 2,
                    'pertanyaan' => 'Sesuai dengan bukti no. 2, bagaimana Anda memastikan bahwa slide presentasi yang Anda buat memenuhi prinsip desain visual?',
                    'jawaban' => '', // KOSONG
                    'pencapaian' => '', // KOSONG
                ],
            ],
            'rekomendasi' => '',
            'catatan' => '',
        ];
    }

    /**
     * Data yang SUDAH DIISI oleh Asesor, yang akan dilihat oleh Admin.
     * Di dunia nyata, ini diambil dari record database yang sudah di-update.
     */
    private function getFilledDataIA09() 
    {
        $data = $this->getTemplateIA09();
        
        // --- Simulasi ISIAN ASESOR ---
        $data['pertanyaan'][0]['jawaban'] = 'Saya menggunakan fitur Data Validation di Excel untuk membatasi tipe data dan rentang nilai yang dapat dimasukkan.';
        $data['pertanyaan'][0]['pencapaian'] = 'Ya'; 
        
        $data['pertanyaan'][1]['jawaban'] = 'Saya berpedoman pada prinsip KISS (Keep It Short and Simple) dan 5x5 Rule, serta memastikan kontras warna yang memadai.';
        $data['pertanyaan'][1]['pencapaian'] = 'Ya';
        
        $data['rekomendasi'] = 'Asesi telah memenuhi pencapaian seluruh kriteria untuk kerja, direkomendasikan **KOMPETEN**';
        $data['catatan'] = 'Asesi menunjukkan pemahaman yang sangat baik, terutama pada aspek validasi data.';
        
        // --- Simulasi TANDA TANGAN ---
        $data['info_umum']['asesi_ttd_tgl'] = '2025-11-30';
        $data['info_umum']['asesor_ttd_tgl'] = date('Y-m-d');
        $data['info_umum']['asesor_ttd_file'] = 'base64_ttd_asesor'; // Data TTD
        
        return $data;
    }

    // =======================================================
    // METODE UNTUK ASESOR (Form Edit/Isi)
    // =======================================================

    /**
     * Menampilkan form wawancara (IA.09) untuk Asesor.
     * Di sini, data yang ditampilkan bisa kosong (jika belum pernah diisi)
     * atau terisi sebagian (jika sudah disimpan draft).
     */
    public function showWawancaraAsesor()
    {
        // Di sini Anda bisa menggunakan $this->getTemplateIA09() untuk simulasi belum diisi,
        // atau $this->getFilledDataIA09() jika Anda ingin menguji mode edit oleh Asesor.
        $dataIA09 = $this->getTemplateIA09(); 
        
        return view('frontend.IA09_asesor', compact('dataIA09'));
    }

    /**
     * Menyimpan atau memperbarui data wawancara yang diisi Asesor.
     */
    public function storeWawancara(Request $request)
    {
        // Logika untuk validasi dan menyimpan data $request ke database
        // Misalnya: IA09::updateOrCreate([...], $request->all());

        return redirect()->back()->with('success', 'Data wawancara berhasil disimpan.');
    }

    // =======================================================
    // METODE UNTUK ADMIN (View Read-Only)
    // =======================================================

    /**
     * Menampilkan hasil wawancara (IA.09) untuk Admin (Read-Only).
     * Data yang ditampilkan harus yang sudah diisi penuh oleh Asesor.
     */
    public function showWawancaraAdmin()
    {
        // Admin melihat data yang SUDAH DIISI oleh Asesor
        $dataIA09 = $this->getFilledDataIA09(); 

        return view('frontend.IA09_admin', compact('dataIA09'));
    }
}