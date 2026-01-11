<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponHasilAk03;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ak03Controller extends Controller
{
    // Daftar Pertanyaan (Bisa juga diambil dari DB PoinAk03 jika sudah di-seed)
    private $pertanyaan = [
        "Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi",
        "Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya",
        "Asesor memberikan kesempatan untuk mendiskusikan/ menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen",
        "Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki",
        "Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen",
        "Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen",
        "Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya",
        "Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya",
        "Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen",
        "Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen"
    ];

    // ============================================================================
    // 🛡️ HELPER: CEK ROLE
    // ============================================================================
    private function isAdmin()
    {
        $role = Auth::user()->role->nama_role ?? '';
        return in_array($role, ['admin', 'superadmin']);
    }

    private function isAsesor()
    {
        return Auth::check() && Auth::user()->role->nama_role === 'asesor';
    }

    public function create($id_sertifikasi)
    {
        $query = DataSertifikasiAsesi::with(['jadwal.skema', 'jadwal.asesor', 'asesi']);

        // Jika bukan admin/asesor, batasi hanya data milik asesi yang bersangkutan
        if (!$this->isAdmin() && !$this->isAsesor()) {
            $asesi = Auth::user()->asesi;
            if (!$asesi) {
                abort(403, 'Profil asesi tidak ditemukan.');
            }
            $query->where('id_asesi', $asesi->id_asesi);
        }

        $sertifikasi = $query->findOrFail($id_sertifikasi);

        // Ambil jawaban yang sudah pernah diisi (Mapping berdasarkan urutan/index pertanyaan)
        $jawaban = ResponHasilAk03::where('id_data_sertifikasi_asesi', $id_sertifikasi)
            ->get()
            ->keyBy('id_poin_ak03');

        return view('frontend.AK_03.FR_AK_03', [
            'sertifikasi' => $sertifikasi,
            'questions' => $this->pertanyaan,
            'jawaban' => $jawaban
        ]);
    }

    public function store(Request $request, $id_sertifikasi)
    {
        $query = DataSertifikasiAsesi::query();

        // Jika bukan admin/asesor, batasi hanya data milik asesi yang bersangkutan
        if (!$this->isAdmin() && !$this->isAsesor()) {
            $asesi = Auth::user()->asesi;
            if (!$asesi) {
                abort(403, 'Profil asesi tidak ditemukan.');
            }
            $query->where('id_asesi', $asesi->id_asesi);
        }

        $sertifikasi = $query->findOrFail($id_sertifikasi);

        // Validasi
        $request->validate([
            'umpan_balik' => 'required|array',
            'komentar_lain' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Jawaban Per Poin (Looping)
            foreach ($this->pertanyaan as $index => $soal) {
                // Index di array mulai dari 0, tapi ID poin di DB biasanya mulai dari 1
                $idPoin = $index + 1; 
                
                $hasil = $request->umpan_balik[$index] ?? null; // ya/tidak
                $catatan = $request->catatan[$index] ?? null;

                if ($hasil) {
                    ResponHasilAk03::updateOrCreate(
                        [
                            'id_data_sertifikasi_asesi' => $id_sertifikasi,
                            'id_poin_ak03' => $idPoin
                        ],
                        [
                            'hasil' => $hasil, // pastikan kolom di DB tipe enum/varchar ('ya','tidak')
                            'catatan' => $catatan
                        ]
                    );
                }
            }

            // 2. Simpan Komentar Umum ke Tabel Induk
            $sertifikasi->catatan_asesi_AK03 = $request->komentar_lain;
            // $sertifikasi->status_sertifikasi = 'umpan_balik_selesai'; // Uncomment jika ingin update status
            $sertifikasi->save();

            DB::commit();
            return redirect()->route('tracker')->with('success', 'Umpan Balik (AK-03) berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan Template Form FR.AK.03 (Admin Master View)
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
            'targetRoute' => 'ak03.create',
            'buttonLabel' => 'FR.AK.03',
        ]);
    }
}