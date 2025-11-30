<?php

// app/Http/Controllers/IA09Controller.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IA09Controller extends Controller
{
    public function showWawancara()
    {
        // Data Dummy untuk FR.IA.09. PW-PERTANYAAN WAWANCARA
        $dataIA09 = [
            'skema' => [
                'judul' => 'Sertifikasi Operator Komputer Madya',
                'nomor' => 'OKM-01',
            ],
            'info_umum' => [
                'tuk_type' => 'Mandiri', // Sewaktu, Tempat Kerja, atau Mandiri
                'nama_asesor' => 'Dr. Rina Kusuma, M.Kom.',
                'nama_asesi' => 'Bambang Sudiro',
                'tanggal' => date('Y-m-d'), // Tanggal hari ini
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
                [
                    'kelompok' => 'Pengolahan Data',
                    'kode' => 'TIK.OK02.001.01',
                    'judul' => 'Mengolah Data dengan Aplikasi Spreadsheet',
                ],
                [
                    'kelompok' => 'Presentasi',
                    'kode' => 'TIK.OK02.002.01',
                    'judul' => 'Membuat Materi Presentasi',
                ],
            ],
            'bukti_portofolio' => [
                '1. Laporan hasil pengolahan data spreadsheet',
                '2. File presentasi (.pptx)',
                '3. Sertifikat pelatihan terkait',
                '4. Surat Keterangan Pengalaman Kerja',
            ],
            'pertanyaan' => [
                [
                    'no' => 1,
                    'pertanyaan' => 'Sesuai dengan bukti no. 1, jelaskan langkah-langkah Anda dalam memvalidasi data yang dimasukkan ke spreadsheet.',
                    'jawaban' => 'Data divalidasi dengan fungsi IF dan VLOOKUP.',
                    'pencapaian' => 'Ya', // Opsi: 'Ya' atau 'Tidak'
                ],
                [
                    'no' => 2,
                    'pertanyaan' => 'Sesuai dengan bukti no. 2, bagaimana Anda memastikan bahwa slide presentasi yang Anda buat memenuhi prinsip desain visual?',
                    'jawaban' => 'Saya menggunakan template dengan kontras warna yang tinggi dan ukuran font yang standar.',
                    'pencapaian' => 'Ya',
                ],
                [
                    'no' => 3,
                    'pertanyaan' => 'Sesuai dengan bukti no. 4, sebutkan minimal 2 tantangan terbesar yang Anda hadapi dalam pekerjaan tersebut dan bagaimana Anda mengatasinya.',
                    'jawaban' => 'Tantangan adalah inkonsistensi data sumber, diatasi dengan skrip pembersihan data.',
                    'pencapaian' => 'Tidak',
                ],
            ],
            'rekomendasi' => 'Asesi telah memenuhi pencapaian seluruh kriteria untuk kerja, direkomendasikan **KOMPETEN**',
            'catatan' => 'Asesi menunjukkan pemahaman yang baik tentang TIK.OK02.001.01 dan TIK.OK02.002.01.',
        ];

        // Mengirim data ke view IA09.blade.php
        return view('frontend.IA09', compact('dataIA09'));
    }
}