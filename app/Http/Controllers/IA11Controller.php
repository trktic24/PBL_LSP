<?php

namespace App\Http\Controllers;

use App\Models\Ia11;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IA11Controller extends Controller
{
    /**
     * Menampilkan formulir FR.IA.11 berdasarkan ID.
     * Menggunakan kolom 'rancangan_produk' untuk membaca data penilaian Asesor (JSON).
     */
    public function show($id)
    {
        $ia11 = Ia11::findOrFail($id);
        $user = Auth::user();

        // Data penilaian Asesor sudah otomatis di-decode oleh Model berkat $casts = ['rancangan_produk' => 'array']
        $asesor_data = $ia11->rancangan_produk ?? [];

        // [AUTO-LOAD TEMPLATE] Jika rekomendasi masih kosong, ambil dari Master Template
        if (empty($asesor_data['rekomendasi_kelompok']) && empty($asesor_data['rekomendasi_unit'])) {
            $template = MasterFormTemplate::where('id_skema', $ia11->dataSertifikasiAsesi?->jadwal?->id_skema)
                                        ->where('id_jadwal', $ia11->dataSertifikasiAsesi?->id_jadwal)
                                        ->where('form_code', 'FR.IA.11')
                                        ->first();
            
            if (!$template) {
                $template = MasterFormTemplate::where('id_skema', $ia11->dataSertifikasiAsesi?->jadwal?->id_skema)
                                            ->whereNull('id_jadwal')
                                            ->where('form_code', 'FR.IA.11')
                                            ->first();
            }
            if ($template && !empty($template->content)) {
                $asesor_data['rekomendasi_kelompok'] = $template->content['rekomendasi_kelompok'] ?? '';
                $asesor_data['rekomendasi_unit'] = $template->content['rekomendasi_unit'] ?? '';
                $asesor_data['catatan_asesor'] = $template->content['catatan_asesor'] ?? '';
            }
        }
        // [AUTO-LOAD TEMPLATE] Jika belum ada pertanyaan, pakai Master Template atau Statis
        if ($ia11->pertanyaan->isEmpty()) {
            $template = MasterFormTemplate::where('id_skema', $ia11->dataSertifikasiAsesi?->jadwal?->id_skema)
                                        ->where('id_jadwal', $ia11->dataSertifikasiAsesi?->id_jadwal)
                                        ->where('form_code', 'FR.IA.11_QUESTIONS') // Assuming a separate template for questions
                                        ->first();
                                        
            if (!$template) {
                $template = MasterFormTemplate::where('id_skema', $ia11->dataSertifikasiAsesi?->jadwal?->id_skema)
                                            ->whereNull('id_jadwal')
                                            ->where('form_code', 'FR.IA.11_QUESTIONS')
                                            ->first();
            }
            
            $defaultQuestions = [
                "Apakah produk hasil kerja memenuhi spesifikasi yang ditetapkan?",
                "Apakah kriteria keberterimaan produk dipenuhi secara keseluruhan?",
                "Apakah proses pembuatan produk mengikuti standar keselamatan kerja (K3)?",
                "Apakah produk menunjukkan tingkat kemahiran yang dipersyaratkan?"
            ];

            // If template exists and has content, use it; otherwise, use default questions
            $questions = ($template && !empty($template->content) && is_array($template->content)) ? $template->content : $defaultQuestions;

            foreach ($questions as $qText) {
                \App\Models\PertanyaanIa11::create([
                    'id_ia11' => $ia11->id, // Use $ia11->id for the foreign key
                    'pertanyaan' => $qText,
                    'penilaian' => null 
                ]);
            }
            // Refresh relation
            $ia11->load('pertanyaan');
        }

        $data = [
            'ia11' => $ia11,
            'user' => $user, // KRUSIAL: Objek user (beserta role) dikirim ke view
            'asesor_data' => $asesor_data, // Data penilaian Asesor yang sudah di-array

            // Data dummy/relasi (Sesuaikan dengan relasi yang sebenarnya)
            'judul_skema' => 'Web Developer Profesional',
            'nomor_skema' => 'SKM-WD-01',
            'nama_asesor' => 'Budi Santoso', // Ambil dari relasi yang benar
            'nama_asesi' => 'Siti Aminah',   // Ambil dari relasi yang benar
            'tanggal_sekarang' => $ia11->tanggal_pengoperasian ?? Carbon::now()->toDateString(),
        ];

        return view('frontend.FR_IA_11', $data);
    }

    /**
     * Memperbarui data FR.IA.11. Otorisasi berdasarkan peran.
     */
    public function update(Request $request, $id)
    {
        $ia11 = Ia11::findOrFail($id);
        $user = Auth::user();
        $role = $user->role ?? 'guest';

        if ($role === 'admin') {
            return back()->with('error', 'Admin hanya memiliki hak lihat (view-only).');
        }

        // ===================================
        // OTORISASI ASESOR (Menyimpan penilaian ke kolom rancangan_produk (JSON))
        // ===================================
        if ($role === 'asesor') {
            // Data yang akan disimpan Asesor ke dalam JSON
            $asesor_payload = [
                // Input Asesor
                'tuk_type' => $request->input('tuk_type'),
                'tanggal_asesmen' => $request->input('tanggal_asesmen'),

                // Penilaian Checkbox (Gunakan request->has() untuk boolean)
                'penilaian' => [
                    'h1a_ya' => $request->has('h1a_ya'),
                    'p1a_ya' => $request->has('p1a_ya'),
                    'h1b_ya' => $request->has('h1b_ya'),
                    'p1b_ya' => $request->has('p1b_ya'),
                    'h2a_ya' => $request->has('h2a_ya'),
                    'p2a_ya' => $request->has('p2a_ya'),
                    'h3a_ya' => $request->has('h3a_ya'),
                    'p3a_ya' => $request->has('p3a_ya'),
                    'h3b_ya' => $request->has('h3b_ya'),
                    'p3b_ya' => $request->has('p3b_ya'),
                    'h3c_ya' => $request->has('h3c_ya'),
                    'p3c_ya' => $request->has('p3c_ya'),
                    // Tambahkan semua 20 checkbox di sini
                ],

                // Rekomendasi & Catatan
                'rekomendasi_kelompok' => $request->input('rekomendasi_kelompok'),
                'rekomendasi_unit' => $request->input('rekomendasi_unit'),
                'catatan_asesor' => $request->input('catatan_asesor'),

                // Tanda Tangan dan Penyusun/Validator
                'ttd_asesor' => $request->input('ttd_asesor'),
                'penyusun_nama_1' => $request->input('penyusun_nama_1'),
                'validator_nama_1' => $request->input('validator_nama_1'),
                // ... tambahkan data penyusun/validator lain ...
            ];

            // Simpan seluruh payload Asesor sebagai JSON di kolom 'rancangan_produk'
            $ia11->rancangan_produk = $asesor_payload; // Laravel akan otomatis meng-encode ke JSON karena ada $casts di Model

            // Simpan data teknis awal (jika Asesor juga mengubahnya)
            $ia11->nama_produk = $request->input('nama_produk');
            $ia11->standar_industri = $request->input('standar_industri');
            // ... (kolom lain yang diizinkan diisi/diubah asesor di data awal)

            $ia11->save();
            return back()->with('success', 'Formulir FR.IA.11 berhasil diperbarui oleh Asesor.');
        }

        // ===================================
        // OTORISASI ASESI (Hanya mengisi data produk dan TTD)
        // ===================================
        if ($role === 'asesi') {
            $validatedData = $request->validate([
                'nama_produk' => 'nullable|string',
                'standar_industri' => 'nullable|string',
                'tanggal_pengoperasian' => 'nullable|date',
                'gambar_produk' => 'nullable|string',
                // KARENA TIDAK ADA KOLOM TTD ASESI, kita coba simpan di kolom TERTENTU,
                // tapi ini BUKAN solusi yang bersih. Kita asumsikan TTD Asesi juga di payload JSON Asesor
                // atau disimpan di 'gambar_produk' atau kolom string yang tersisa.
            ]);

            // Simpan data produk awal
            $ia11->fill($validatedData);
            $ia11->save();

            return back()->with('success', 'Formulir FR.IA.11 berhasil diperbarui oleh Asesi.');
        }

        return back()->with('error', 'Anda tidak memiliki otorisasi untuk mengubah data ini.');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Tinjau Instrumen) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.IA.11')
                                    ->first();
        
        // Default values if no template exists
        $content = $template ? $template->content : [
            'rekomendasi_kelompok' => '',
            'rekomendasi_unit' => '',
            'catatan_asesor' => ''
        ];

        return view('Admin.master.skema.template.ia11', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'content' => $content
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema & Jadwal
     */
    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        $request->validate([
            'rekomendasi_kelompok' => 'nullable|string',
            'rekomendasi_unit' => 'nullable|string',
            'catatan_asesor' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.IA.11'
            ],
            [
                'content' => [
                    'rekomendasi_kelompok' => $request->rekomendasi_kelompok,
                    'rekomendasi_unit' => $request->rekomendasi_unit,
                    'catatan_asesor' => $request->catatan_asesor,
                ]
            ]
        );

        return redirect()->back()->with('success', 'Templat IA-11 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.IA.11 (Admin Master View) - DEPRECATED for management
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::with(['kelompokPekerjaan.unitKompetensi'])->findOrFail($id_skema);
        
        // Mock data sertifikasi
        $sertifikasi = new \App\Models\DataSertifikasiAsesi();
        $sertifikasi->id_data_sertifikasi_asesi = 0;
        
        $asesi = new \App\Models\Asesi(['nama_lengkap' => 'Template Master']);
        $sertifikasi->setRelation('asesi', $asesi);
        
        $jadwal = new \App\Models\Jadwal(['tanggal_pelaksanaan' => now()]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('asesor', new \App\Models\Asesor(['nama_lengkap' => 'Nama Asesor']));
        $jadwal->setRelation('jenisTuk', new \App\Models\JenisTUK(['jenis_tuk' => 'Tempat Kerja']));
        $sertifikasi->setRelation('jadwal', $jadwal);

        $defaultPoints = [
            "Prosedur Asesmen",
            "Instruksi Perangkat Asesmen",
            "Sesuai dengan Unit Kompetensi",
            "Memberikan Panduan bagi Asesor"
        ];

        $ia11 = new \App\Models\Ia11([
            'rancangan_produk' => [
                'rekomendasi_kelompok' => 'Lanjut',
                'rekomendasi_unit' => 'Lanjut',
                'catatan_asesor' => '-'
            ]
        ]);
        $ia11->setRelation('dataSertifikasiAsesi', $sertifikasi);

        $pertanyaan = collect();
        foreach ($defaultPoints as $pText) {
            $pertanyaan->push(new \App\Models\PertanyaanIa11([
                'pertanyaan' => $pText,
                'penilaian' => 'ya'
            ]));
        }
        $ia11->setRelation('pertanyaan', $pertanyaan);

        $user = new \stdClass();
        $user->role = 'admin';

        return view('frontend.FR_IA_11', [
            'ia11' => $ia11,
            'user' => $user,
            'asesor_data' => $ia11->rancangan_produk,
            'judul_skema' => $skema->nama_skema,
            'nomor_skema' => $skema->kode_unit,
            'nama_asesor' => $jadwal->asesor->nama_lengkap,
            'nama_asesi' => $asesi->nama_lengkap,
            'tanggal_sekarang' => now()->toDateString(),
            'isMasterView' => true,
        ]);
    }
}
