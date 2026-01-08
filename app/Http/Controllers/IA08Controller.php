<?php

namespace App\Http\Controllers;

use App\Models\DataSertifikasiAsesi;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use App\Models\DataPortofolio;
use App\Models\BuktiPortofolioIA08IA09;
use App\Models\IA08;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // <--- TAMBAHKAN INI

class IA08Controller extends Controller
{
    public function show($id_sertifikasi_asesi)
    {
        $data = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.tuk',
            'jadwal.skema',
            'jadwal.asesor',
            'jadwal.jenisTuk',
        ])->findOrFail($id_sertifikasi_asesi);

        $kelompokPekerjaan = KelompokPekerjaan::where(
            'id_skema',
            $data->jadwal->skema->id_skema
        )->get();

        $unitKompetensi = UnitKompetensi::whereIn(
            'id_kelompok_pekerjaan',
            $kelompokPekerjaan->pluck('id_kelompok_pekerjaan')
        )
            ->orderBy('urutan')
            ->get();

        $buktiPortofolio = DataPortofolio::where(
            'id_data_sertifikasi_asesi',
            $id_sertifikasi_asesi
        )
            ->with('buktiPortofolioIA08IA09')
            ->get()
            ->map(function ($item) {

                $ia08 = $item->buktiPortofolioIA08IA09->first();

                $item->array_valid = $ia08 ? explode(', ', $ia08->is_valid) : [];
                $item->array_asli = $ia08 ? explode(', ', $ia08->is_asli) : [];
                $item->array_terkini = $ia08 ? explode(', ', $ia08->is_terkini) : [];
                $item->array_memadai = $ia08 ? explode(', ', $ia08->is_memadai) : [];

                return $item;
            });


        // 🔑 Ambil data IA08 (untuk recall UI rekomendasi)
        $ia08 = IA08::where(
            'id_data_sertifikasi_asesi',
            $id_sertifikasi_asesi
        )->first();

        // 🔒 Lock form jika data sudah tersimpan atau readonly (admin)
        $isLocked = $ia08 || request()->get('is_readonly', false);

        return view(
            'frontend.IA_08.IA_08',
            compact(
                'buktiPortofolio',
                'kelompokPekerjaan',
                'unitKompetensi',
                'ia08',
                'isLocked'
            ) + [
                'id_data_sertifikasi_asesi' => $id_sertifikasi_asesi,
                'skema' => $data->jadwal->skema,
                'jenisTuk' => $data->jadwal->jenisTuk,
                'asesor' => $data->jadwal->asesor,
                'asesi' => $data->asesi,
                'data_sesi' => [
                    'tanggal_asesmen' => $data->jadwal->tanggal_pelaksanaan
                        ? date('Y-m-d', strtotime($data->jadwal->tanggal_pelaksanaan))
                        : now()->format('Y-m-d'),
                ],
            ]
        );
    }

    public function store(Request $request)
    {
        // ===============================
        // 🔒 ADMIN READ-ONLY (DARI MIDDLEWARE)
        // ===============================
        if ($request->get('is_readonly')) {
            abort(403, 'Admin tidak diperbolehkan menyimpan data IA-08.');
        }

        // ===============================
        // VALIDASI DASAR
        // ===============================
        $request->validate([
            'id_portofolio' => 'required|array',
            'id_data_sertifikasi_asesi' => 'required',
            'rekomendasi' => 'required|in:kompeten,perlu observasi lanjut',
        ]);

        // ===============================
        // 🔒 CEGAH SUBMIT ULANG
        // ===============================
        $existingIA08 = IA08::where(
            'id_data_sertifikasi_asesi',
            $request->id_data_sertifikasi_asesi
        )->first();

        if ($existingIA08) {
            return back()->withErrors([
                'locked' => 'Data IA-08 sudah disimpan dan tidak dapat diubah kembali.',
            ]);
        }

        // ===============================
        // VALIDASI CHECKBOX BUKTI PORTOFOLIO
        // ===============================
        foreach ($request->id_portofolio as $id_portofolio) {
            $groups = [
                'valid' => $request->valid[$id_portofolio] ?? [],
                'asli' => $request->asli[$id_portofolio] ?? [],
                'terkini' => $request->terkini[$id_portofolio] ?? [],
                'memadai' => $request->memadai[$id_portofolio] ?? [],
            ];

            foreach ($groups as $values) {
                if (empty($values)) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'bukti_portofolio' =>
                                'Semua bukti portofolio WAJIB diisi (Valid, Asli, Terkini, dan Memadai).',
                        ]);
                }
            }
        }

        // ===============================
        // SIMPAN DATA (TRANSACTION)
        // ===============================
        DB::transaction(function () use ($request) {

            $ia08 = IA08::create([
                'id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi,
                'rekomendasi' => $request->rekomendasi,

                'kelompok_pekerjaan' =>
                    $request->rekomendasi === 'perlu observasi lanjut'
                    ? $request->kelompok_pekerjaan
                    : null,

                'unit_kompetensi' =>
                    $request->rekomendasi === 'perlu observasi lanjut'
                    ? $request->unit_kompetensi
                    : null,

                'elemen' =>
                    $request->rekomendasi === 'perlu observasi lanjut'
                    ? $request->elemen
                    : null,

                'kuk' =>
                    $request->rekomendasi === 'perlu observasi lanjut'
                    ? $request->kuk
                    : null,
            ]);

            foreach ($request->id_portofolio as $id_portofolio) {
                BuktiPortofolioIA08IA09::create([
                    'id_portofolio' => $id_portofolio,
                    'id_ia08' => $ia08->id_ia08,

                    'is_valid' => collect($request->valid[$id_portofolio])->implode(', '),
                    'is_asli' => collect($request->asli[$id_portofolio])->implode(', '),
                    'is_terkini' => collect($request->terkini[$id_portofolio])->implode(', '),
                    'is_memadai' => collect($request->memadai[$id_portofolio])->implode(', '),

                    'daftar_pertanyaan_wawancara' =>
                        $request->pertanyaan[$id_portofolio] ?? null,

                    'kesimpulan_jawaban_asesi' =>
                        $request->kesimpulan[$id_portofolio] ?? null,
                ]);
            }
        });

        return back()->with(
            'success',
            'Verifikasi portofolio & rekomendasi asesor berhasil disimpan.'
        );
    }

    /**
     * CETAK PDF FR.IA.08
     */
    public function cetakPDF($id_sertifikasi_asesi)
    {
        // 1. Ambil Data Sertifikasi
        $data = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_sertifikasi_asesi);

        // 2. Ambil Unit Kompetensi
        $kelompokPekerjaan = \App\Models\KelompokPekerjaan::where(
            'id_skema',
            $data->jadwal->skema->id_skema
        )->get();

        $unitKompetensi = \App\Models\UnitKompetensi::whereIn(
            'id_kelompok_pekerjaan',
            $kelompokPekerjaan->pluck('id_kelompok_pekerjaan')
        )
            ->orderBy('urutan')
            ->get();

        // 3. Ambil Bukti Portofolio & Hasil Verifikasi IA.08
        // Kita butuh data V-A-T-M yang tersimpan di tabel 'bukti_portofolio_ia08_ia09'
        $buktiPortofolio = \App\Models\DataPortofolio::where(
            'id_data_sertifikasi_asesi',
            $id_sertifikasi_asesi
        )
            ->with('buktiPortofolioIA08IA09')
            ->get()
            ->map(function ($item) {
                // Ambil record verifikasi pertama (jika ada)
                $ia08 = $item->buktiPortofolioIA08IA09->first();

                // Explode string "V, A, T, M" jadi array biar gampang dicek di view
                $item->array_valid = $ia08 ? explode(', ', $ia08->is_valid) : [];
                $item->array_asli = $ia08 ? explode(', ', $ia08->is_asli) : [];
                $item->array_terkini = $ia08 ? explode(', ', $ia08->is_terkini) : [];
                $item->array_memadai = $ia08 ? explode(', ', $ia08->is_memadai) : [];

                return $item;
            });

        // 4. Ambil Data Rekomendasi (IA.08 Header)
        $ia08Header = IA08::where('id_data_sertifikasi_asesi', $id_sertifikasi_asesi)->first();

        // 5. Render PDF
        $pdf = Pdf::loadView('pdf.ia_08', [
            'data' => $data,
            'unitKompetensi' => $unitKompetensi,
            'buktiPortofolio' => $buktiPortofolio,
            'ia08Header' => $ia08Header
        ]);

        return $pdf->stream('FR_IA_08.pdf');
    }

    /**
     * Menampilkan Template Form FR.IA.08 (Admin Master View)
     */
    public function adminShow($id_skema)
    {
        $skema = \App\Models\Skema::findOrFail($id_skema);

        $query = \App\Models\DataSertifikasiAsesi::with([
            'asesi.dataPekerjaan',
            'jadwal.skema',
            'jadwal.masterTuk',
            'jadwal.asesor'
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

        return view('Admin.profile_asesor.daftar_asesi', [
            'pendaftar' => $pendaftar,
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'isMasterView' => true,
            'sortColumn' => request('sort', 'nama_lengkap'),
            'sortDirection' => request('direction', 'asc'),
            'perPage' => request('per_page', 10),
            'targetRoute' => 'ia08.show',
            'buttonLabel' => 'FR.IA.08',
        ]);
    }
}