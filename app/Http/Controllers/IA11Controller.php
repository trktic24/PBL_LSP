<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ia11;
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
     * Menampilkan Template Form FR.IA.11 (Admin Master View)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'responApl2Ia01',
            'responBuktiAk01',
            'lembarJawabIa05',
            'komentarAk05'
        ])->whereHas('jadwal', function($q) use ($id_skema) {
            $q->where('id_skema', $id_skema);
        });

        if (request('search')) {
            $search = request('search');
            $query->whereHas('asesi', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%");
            });
        }

        $pendaftar = $query->paginate(request('per_page', 10))->withQueryString();

        $user = auth()->user();
        $asesor = new \App\Models\Asesor();
        $asesor->id_asesor = 0;
        $asesor->nama_lengkap = $user ? $user->name : 'Administrator';
        $asesor->pas_foto = $user ? $user->profile_photo_path : null;
        $asesor->status_verifikasi = 'approved';
        $asesor->setRelation('skemas', collect());
        $asesor->setRelation('jadwals', collect());
        $asesor->setRelation('skema', null);

        $jadwal = new \App\Models\Jadwal([
            'tanggal_pelaksanaan' => now(),
            'waktu_mulai' => '08:00',
        ]);
        $jadwal->setRelation('skema', $skema);
        $jadwal->setRelation('masterTuk', new \App\Models\MasterTUK(['nama_lokasi' => 'Semua TUK (Filter Skema)']));

        return view('Admin.master.skema.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'ia11.show',
            'buttonLabel' => 'FR.IA.11',
        ]);
    }
}
