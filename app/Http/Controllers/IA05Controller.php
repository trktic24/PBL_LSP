<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SoalIA05;
use App\Models\KunciJawabanIA05;
use App\Models\LembarJawabIA05;
use App\Models\DataSertifikasiAsesi;
use App\Models\Skema;
use Barryvdh\DomPDF\Facade\Pdf;

class IA05Controller extends Controller
{
    // FUNGSI BANTUAN: Mapping Role ID ke Teks (Supaya View tidak error)
    // FUNGSI BANTUAN: Mapping Role ID ke Teks (Supaya View tidak error)
    private function getRoleText($user)
    {
        if (!$user)
            return 'guest';

        // Gunakan relasi role->nama_role (admin, asesi, asesor)
        // Pastikan model User punya relasi 'role' yang meload tabel roles
        return $user->role ? $user->role->nama_role : 'guest';
    }

    /**
     * =================================================================
     * FORM A: SOAL (untuk Admin) & LEMBAR JAWAB (untuk Asesi)
     * =================================================================
     */
    public function showSoalForm(Request $request, $id_asesi)
    {
        // --- [HIDUPKAN] DATA ASLI (REAL AUTH) ---
        $user = Auth::user();
        $roleText = $this->getRoleText($user); // Ganti nama fungsi biar jelas
        // ----------------------------------------

        $asesi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.asesor',
            'jadwal.jenisTuk',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_asesi);        
        $jadwal = $asesi->jadwal;
        $semua_soal = SoalIA05::orderBy('id_soal_ia05')->get();

        $data_jawaban_asesi = collect();

        // Logika ambil jawaban jika role asesi/asesor
        if ($user->role_id == 2 || $user->role_id == 3) {
            $data_jawaban_asesi = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
                ->pluck('jawaban_asesi_ia05', 'id_soal_ia05');
        }

        return view('frontend.FR_IA_05_A', [
            'user' => $user,
            'role' => $roleText, // Kirim sebagai variabel terpisah
            'asesi' => $asesi,
            'semua_soal' => $semua_soal,
            'data_jawaban_asesi' => $data_jawaban_asesi,
            "jadwal" => $jadwal
        ]);
    }

    /**
     * [KHUSUS ADMIN] Menyimpan data Soal (Form A)
     */
    public function storeSoal(Request $request)
    {
        $request->validate(['soal' => 'required|array']);

        DB::beginTransaction();
        try {
            if ($request->has('soal')) {
                foreach ($request->soal as $id_soal => $data) {
                    SoalIA05::updateOrCreate(
                        ['id_soal_ia05' => $id_soal],
                        [
                            'soal_ia05' => $data['pertanyaan'],
                            'opsi_jawaban_a' => $data['opsi_a'],
                            'opsi_jawaban_b' => $data['opsi_b'],
                            'opsi_jawaban_c' => $data['opsi_c'],
                            'opsi_jawaban_d' => $data['opsi_d'] ?? null,
                        ]
                    );
                }
            }

            // 2. INSERT SOAL BARU (Dari Tombol Tambah Soal)
            if ($request->has('new_soal')) {
                foreach ($request->new_soal as $index => $data) {
                    SoalIA05::create([
                        // ID biasanya Auto Increment, jadi tidak perlu diisi
                        'soal_ia05' => $data['pertanyaan'],
                        'opsi_jawaban_a' => $data['opsi_a'],
                        'opsi_jawaban_b' => $data['opsi_b'],
                        'opsi_jawaban_c' => $data['opsi_c'],
                        'opsi_jawaban_d' => $data['opsi_d'] ?? null,
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Bank Soal (IA-05 A) berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan soal: ' . $e->getMessage());
        }
    }

    /**
     * [KHUSUS ASESI] Menyimpan jawaban Asesi (Form A)
     */
    public function storeJawabanAsesi(Request $request, $id_asesi)
    {
        $request->validate(['jawaban' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->jawaban as $id_soal => $pilihan_jawaban) {
                LembarJawabIA05::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $id_asesi,
                        'id_soal_ia05' => $id_soal,
                    ],
                    [
                        'jawaban_asesi_ia05' => $pilihan_jawaban, // <-- Perbaiki nama kolom (hapus teks_)
                        'pencapaian_ia05' => null, // Reset penilaian jadi kosong
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Jawaban Anda berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    /**
     * =================================================================
     * FORM B: KUNCI JAWABAN (Admin & Asesor)
     * =================================================================
     */
    public function showKunciForm(Request $request)
    {
        // --- [HIDUPKAN] DATA ASLI (REAL AUTH) ---
        $id_asesi = $request->query('ref'); 

        // Validasi kalau ID-nya kosong/diisengin user
        if (!$id_asesi) {
            abort(404, 'ID Asesi tidak ditemukan. Pastikan URL memiliki ?ref=ID');
        }
        $asesi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.asesor',
            'jadwal.jenisTuk',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_asesi);        
        $jadwal = $asesi->jadwal;
        $user = Auth::user();
        $roleText = $this->getRoleText($user);
        // ----------------------------------------

        // Jika role_id = 2 (Asesi), langsung tolak!
        if ($user->role_id == 2) {
            return abort(403, 'Akses Ditolak. Asesi tidak boleh melihat Kunci Jawaban!');
        }

        $semua_soal = SoalIA05::orderBy('id_soal_ia05')->get();
        $kunci_jawaban = KunciJawabanIA05::pluck('jawaban_benar_ia05', 'id_soal_ia05');
        $skema_info = Skema::first();

        return view('frontend.FR_IA_05_B', [
            'asesi' => $asesi,
            'user' => $user,
            'role' => $roleText,
            'semua_soal' => $semua_soal,
            'kunci_jawaban' => $kunci_jawaban,
            'skema_info' => $skema_info,
             "jadwal" => $jadwal
        ]);
    }

    /**
     * [KHUSUS ADMIN] Menyimpan Kunci Jawaban (Form B)
     */
    public function storeKunci(Request $request)
    {
        $request->validate(['kunci' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->kunci as $id_soal => $teks_kunci) {
                KunciJawabanIA05::updateOrCreate(
                    ['id_soal_ia05' => $id_soal],
                    [
                        'teks_kunci_jawaban_ia05' => $teks_kunci,
                        'nomor_kunci_jawaban_ia05' => 1,
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Kunci Jawaban (IA-05 B) berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan kunci jawaban: ' . $e->getMessage());
        }
    }

    /**
     * =================================================================
     * FORM C: HASIL JAWABAN & PENILAIAN (Admin & Asesor)
     * =================================================================
     */
    public function showJawabanForm(Request $request, $id_asesi)
    {
        // --- [HIDUPKAN] DATA ASLI (REAL AUTH) ---
        $user = Auth::user();
        $roleText = $this->getRoleText($user);
        // ----------------------------------------

        // Jika role_id = 2 (Asesi), langsung tolak!
        if ($user->role_id == 2) {
            return abort(403, 'Akses Ditolak. Asesi tidak boleh melihat Kunci Jawaban!');
        }

        $asesi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.asesor',
            'jadwal.jenisTuk',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_asesi);
        $jadwal = $asesi->jadwal;
        $semua_soal = SoalIA05::orderBy('id_soal_ia05')->get();

        $kunci_jawaban = KunciJawabanIA05::pluck('jawaban_benar_ia05', 'id_soal_ia05');

        $lembar_jawab = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
            ->get()
            ->keyBy('id_soal_ia05');

        // Ambil Umpan Balik
        $data_feedback = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
                            ->whereNotNull('umpan_balik') 
                            ->first();

        // Kalau ketemu, ambil isinya. Kalau tidak, string kosong.
        // PENTING: Nama properti harus 'umpan_balik' (sesuai DB), JANGAN 'umpan_balik_ia05'
        $umpan_balik = $data_feedback ? $data_feedback->umpan_balik : '';

        return view('frontend.FR_IA_05_C', [
            'user' => $user,
            'role' => $roleText, // Pass role variable
            'asesi' => $asesi,
            'semua_soal' => $semua_soal,
            'kunci_jawaban' => $kunci_jawaban,
            'lembar_jawab' => $lembar_jawab,
            'umpan_balik' => $umpan_balik,
            "jadwal" => $jadwal
        ]);
    }

    /**
     * [KHUSUS ASESOR] Menyimpan Penilaian Asesor (Form C)
     */
    public function storePenilaianAsesor(Request $request, $id_asesi)
    {
        $request->validate([
            'penilaian' => 'required|array',
            'penilaian.*' => 'required|in:ya,tidak',
            'umpan_balik' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->penilaian as $id_soal => $hasil_penilaian) {
                // Pakai updateOrCreate biar kalau datanya belum ada (kasus ID nyasar), dia otomatis bikin baru
                LembarJawabIA05::updateOrCreate(
                    [
                        'id_data_sertifikasi_asesi' => $id_asesi,
                        'id_soal_ia05' => $id_soal,
                    ],
                    [
                        // Masukin langsung string 'ya' atau 'tidak' ke kolom ENUM
                        'pencapaian_ia05' => $hasil_penilaian,
                    ]
                );
            }

            // Simpan Umpan Balik (Jika ada kolomnya di DB)
            if ($request->has('umpan_balik')) {
                // Pastikan kolom 'umpan_balik_ia05' ada di database kamu
                 LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
                    ->update(['umpan_balik' => $request->umpan_balik]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Penilaian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    /**
     * CETAK PDF FR.IA.05 (Hasil Jawaban & Penilaian)
     */
    public function cetakPDF($id_asesi)
    {
        // 1. Ambil Data Asesi Lengkap
        $asesi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.skema.asesor',
            'jadwal.asesor',                                  // Data Asesor
            'jadwal.jenisTuk',                                // Data TUK
            'jadwal.skema.kelompokPekerjaan.unitKompetensi'
        ])->findOrFail($id_asesi);
        $jadwal = $asesi->jadwal;
        // 2. Ambil Soal (Urut ID)
        $semua_soal = SoalIA05::orderBy('id_soal_ia05')->get();

        // 3. Ambil Jawaban & Penilaian
        $lembar_jawab = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
            ->get()
            ->keyBy('id_soal_ia05');

        // 4. Ambil Umpan Balik (Ambil dari salah satu record jawaban)
       $data_feedback = LembarJawabIA05::where('id_data_sertifikasi_asesi', $id_asesi)
                            ->whereNotNull('umpan_balik') 
                            ->first();
        
        $umpan_balik = $data_feedback ? $data_feedback->umpan_balik : '-';

        // 5. Render PDF
        $pdf = Pdf::loadView('pdf.ia_05', [
            'asesi' => $asesi,
            'semua_soal' => $semua_soal,
            'lembar_jawab' => $lembar_jawab,
            'umpan_balik' => $umpan_balik,
            "jadwal" => $jadwal
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_IA_05_' . $asesi->asesi->nama_lengkap . '.pdf');
    }

    /**
     * [MASTER] Menampilkan editor tamplate (Bank Soal) per Skema
     */
    public function editTemplate($id_skema)
    {
        $skema = Skema::findOrFail($id_skema);
        $semua_soal = SoalIA05::where('id_skema', $id_skema)->orderBy('id_soal_ia05')->get();

        return view('Admin.master.skema.template.ia05', [
            'skema' => $skema,
            'semua_soal' => $semua_soal
        ]);
    }

    /**
     * [MASTER] Simpan/Update soal template per Skema
     */
    public function storeTemplate(Request $request, $id_skema)
    {
        $request->validate([
            'soal' => 'required|array',
            'soal.*.pertanyaan' => 'required|string',
            'soal.*.opsi_a' => 'required|string',
            'soal.*.opsi_b' => 'required|string',
            'soal.*.opsi_c' => 'required|string',
            'soal.*.opsi_d' => 'nullable|string',
            'soal.*.kunci' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->soal as $index => $data) {
                $id_soal = $data['id'] ?? null;
                
                $soal = SoalIA05::updateOrCreate(
                    ['id_soal_ia05' => $id_soal, 'id_skema' => $id_skema],
                    [
                        'id_skema' => $id_skema,
                        'soal_ia05' => $data['pertanyaan'],
                        'opsi_a_ia05' => $data['opsi_a'],
                        'opsi_b_ia05' => $data['opsi_b'],
                        'opsi_c_ia05' => $data['opsi_c'],
                        'opsi_d_ia05' => $data['opsi_d'] ?? '',
                    ]
                );

                // Update Kunci
                KunciJawabanIA05::updateOrCreate(
                    ['id_soal_ia05' => $soal->id_soal_ia05],
                    [
                        'jawaban_benar_ia05' => $data['kunci'],
                        'nomor_kunci_jawaban_ia05' => 1
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Templat Soal IA-05 berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan templat: ' . $e->getMessage());
        }
    }

    /**
     * [MASTER] Hapus soal template
     */
    public function destroyTemplate($id_skema, $id_soal)
    {
        $soal = SoalIA05::where('id_skema', $id_skema)->findOrFail($id_soal);
        $soal->delete(); // Cascade delete kunci handled by DB or manually below
        KunciJawabanIA05::where('id_soal_ia05', $id_soal)->delete();

        return redirect()->back()->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Menampilkan Template Form FR.IA.05 (Admin Master View) - DEPRECATED for management but kept for viewing candidates
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
            'targetRoute' => 'FR_IA_05_C',
            'buttonLabel' => 'FR.IA.05',
            'formName' => 'Pertanyaan Tertulis Pilihan Ganda',
        ]);
    }
}