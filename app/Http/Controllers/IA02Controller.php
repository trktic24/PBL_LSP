<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ia02; 
use App\Models\MasterFormTemplate;
use App\Models\DataSertifikasiAsesi;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class IA02Controller extends Controller
{
    /**
     * Menampilkan halaman FR IA.02
     */
    public function show(string $id_data_sertifikasi_asesi)
    {
        // 1. Ambil data sertifikasi beserta relasinya
        // Menggunakan relasi nested yang lengkap agar data tidak null
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'jadwal.masterTuk',
            'jadwal.skema.asesor',
            // Kita coba panggil unitKompetensi via skema (sesuai perbaikan model sebelumnya)
            // Jika masih error, codingan di bawah akan menghandle manualnya.
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->find($id_data_sertifikasi_asesi);

        if (!$sertifikasi) {
            return redirect()
                ->route('asesor.jadwal.index')
                ->with('error', 'Data Sertifikasi tidak ditemukan.');
        }

        // 2. Ambil data Ia02 untuk sertifikasi ini (jika sudah ada)
        $ia02 = Ia02::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->first();

        // 2.b Jika belum ada, ambil dari Master Template
        if (!$ia02 && $sertifikasi->jadwal) {
            $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                        ->where('form_code', 'FR.IA.02')
                                        ->first();
            if ($template && isset($template->content)) {
                $ia02 = new Ia02([
                    'skenario' => $template->content['skenario'] ?? '',
                    'peralatan' => $template->content['peralatan'] ?? '',
                    'waktu' => $template->content['waktu'] ?? '00:00:00',
                ]);
            }
        }

        // 3. Cek Role (Hanya Admin & Superadmin yang bisa edit)
        // Jika user belum login/auth, default false
        $user = Auth::user();
        $isAdmin = Auth::check() && in_array($user->role_id, [1, 4]);
        $isAsesor = Auth::check() && $user->role_id == 3;

        // 4. Ambil Data Unit Kompetensi
        // Kita gunakan Collection kosong sebagai default
        $daftarUnitKompetensi = collect();

        // Logika Pengambilan Unit Kompetensi (Menggabungkan logika loop manual agar aman)
        if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema) {
            // Jika relasi hasManyThrough di Model Skema sudah benar, kita bisa pakai ini:
            if ($sertifikasi->jadwal->skema->unitKompetensi && $sertifikasi->jadwal->skema->unitKompetensi->isNotEmpty()) {
                $daftarUnitKompetensi = $sertifikasi->jadwal->skema->unitKompetensi;
            }
            // Fallback: Jika relasi langsung gagal, kita loop manual lewat kelompokPekerjaan
            elseif ($sertifikasi->jadwal->skema->kelompokPekerjaan) {
                foreach ($sertifikasi->jadwal->skema->kelompokPekerjaan as $kp) {
                    if ($kp->unitKompetensi) {
                        foreach ($kp->unitKompetensi as $uk) {
                            $daftarUnitKompetensi->push($uk);
                        }
                    }
                }
            }
        }
        
        // Final Check: Warning if empty
        if ($daftarUnitKompetensi->isEmpty()) {
            // Optional: Log or flash warning. View should handle empty collection gracefully.
             // session()->flash('warning', 'Tidak ada Unit Kompetensi ditemukan untuk Skema ini.');
        }

        // 5. Kirim ke View
        return view('frontend.FR_IA_02', [
            'sertifikasi' => $sertifikasi,
            'ia02' => $ia02,
            'skenario' => $ia02, // Alias biar view yang pake $skenario tetap jalan
            'isAdmin' => $isAdmin,
            'isAsesor' => $isAsesor,
            'daftarUnitKompetensi' => $daftarUnitKompetensi,
            'unitKompetensis' => $daftarUnitKompetensi, // Alias juga
            'jadwal' => $sertifikasi->jadwal,
            'skema' => $sertifikasi->jadwal->skema,
            'asesi' => $sertifikasi->asesi,
        ]);
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Skenario) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('form_code', 'FR.IA.02')
                                    ->first();
        
        // Default values if no template exists
        $content = $template ? $template->content : [
            'skenario' => '',
            'peralatan' => '',
            'waktu' => '00:00:00'
        ];

        return view('Admin.master.skema.template.ia02', [
            'skema' => $skema,
            'content' => $content
        ]);
    }

    /**
     * [MASTER] Simpan/Update template per Skema
     */
    public function storeTemplate(Request $request, $id_skema)
    {
        $request->validate([
            'skenario' => 'required|string',
            'peralatan' => 'required|string',
            'waktu' => 'required|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            ['id_skema' => $id_skema, 'form_code' => 'FR.IA.02'],
            [
                'content' => [
                    'skenario' => $request->skenario,
                    'peralatan' => $request->peralatan,
                    'waktu' => $request->waktu,
                ]
            ]
        );

        return redirect()->back()->with('success', 'Templat IA-02 berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman FR IA.02 (Admin Master Preview) - DEPRECATED for management
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
            'targetRoute' => 'ia02.show',
            'buttonLabel' => 'FR.IA.02',
        ]);
    }

    /**
     * Menyimpan atau mengupdate data IA.02
     */
    public function store(Request $request, string $id_sertifikasi)
    {
        // 1. Cek Hak Akses (Security)
        // Hanya admin (1), superadmin (4), atau asesor (3)
        if (!Auth::check() || !in_array(Auth::user()->role_id, [1, 3, 4])) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES UNTUK MENGUBAH DATA INI.');
        }

        // 2. Validasi
        $validated = $request->validate([
            'skenario' => 'required|string',
            'peralatan' => 'required|string',
            'waktu' => 'required|string|max:100',
        ]);

        // 3. Simpan ke Database
        Ia02::updateOrCreate(
            ['id_data_sertifikasi_asesi' => $id_sertifikasi],
            [
                'skenario' => $validated['skenario'],
                'peralatan' => $validated['peralatan'],
                'waktu' => $validated['waktu'],
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Data Instruksi Demonstrasi berhasil disimpan.');
    }

    /**
     * Mencetak PDF (Fungsi ini dikembalikan dari HEAD)
     */
    public function cetakPDF($id)
    {
        // 1. Ambil Data Sertifikasi Lengkap
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema.asesor',
            // Pastikan relasi ini sesuai dengan perbaikan Model Skema & KelompokPekerjaan
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id);

        // 2. Ambil Data Skenario (IA02)
        $skenario = Ia02::where('id_data_sertifikasi_asesi', $id)->first();

        // 3. Ambil Unit Kompetensi (Menggunakan logika Model Skema yang baru)
        // Jika error, pastikan public function unitKompetensi() ada di Model Skema
        $unitKompetensis = collect();

        if ($sertifikasi->jadwal && $sertifikasi->jadwal->skema) {
            // Coba ambil dari relasi hasManyThrough Skema
            $unitKompetensis = $sertifikasi->jadwal->skema->unitKompetensi;

            // Jika kosong, coba ambil manual loop
            if ($unitKompetensis->isEmpty() && $sertifikasi->jadwal->skema->kelompokPekerjaan) {
                foreach ($sertifikasi->jadwal->skema->kelompokPekerjaan as $kp) {
                    foreach ($kp->unitKompetensi as $uk) {
                        $unitKompetensis->push($uk);
                    }
                }
            }
        }

        // 4. Render PDF
        $pdf = Pdf::loadView('pdf.ia_02', [
            'sertifikasi' => $sertifikasi,
            'skenario' => $skenario,
            'unitKompetensis' => $unitKompetensis
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_02_' . ($sertifikasi->asesi->nama_lengkap ?? 'Asesi') . '.pdf');
    }
}