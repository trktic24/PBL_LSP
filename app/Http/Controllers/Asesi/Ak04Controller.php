<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponAk04;
use App\Models\MasterFormTemplate;
use App\Models\Skema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ak04Controller extends Controller
{
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

    // 1. MENAMPILKAN FORM AK-04
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

        // Ambil respon lama (jika ada) untuk ditampilkan kembali (edit mode)
        $respon = ResponAk04::where('id_data_sertifikasi_asesi', $id_sertifikasi)->first();

        // [AUTO-LOAD TEMPLATE]
        $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                    ->where('id_jadwal', $sertifikasi->id_jadwal)
                                    ->where('form_code', 'FR.AK.04')
                                    ->first();
        
        if (!$template) {
            $template = MasterFormTemplate::where('id_skema', $sertifikasi->jadwal->id_skema)
                                        ->whereNull('id_jadwal')
                                        ->where('form_code', 'FR.AK.04')
                                        ->first();
        }

        return view('frontend.FR_AK_04', [
            'sertifikasi' => $sertifikasi,
            'respon' => $respon,
            'template' => $template ? $template->content : null
        ]);
    }

    // 2. MENYIMPAN DATA (STORE)
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
            'banding_1' => 'required|in:ya,tidak',
            'banding_2' => 'required|in:ya,tidak',
            'banding_3' => 'required|in:ya,tidak',
            'alasan_banding' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Konversi "ya" => 1, "tidak" => 0
            $dataSimpan = [
                'id_data_sertifikasi_asesi' => $id_sertifikasi,
                'penjelasan_banding' => $request->banding_1 == 'ya' ? 1 : 0,
                'diskusi_dengan_asesor' => $request->banding_2 == 'ya' ? 1 : 0,
                'melibatkan_orang_lain' => $request->banding_3 == 'ya' ? 1 : 0,
                'alasan_banding' => $request->alasan_banding,
                'tanggal_pengajuan' => now(), // Tambahkan tanggal jika ada kolomnya
            ];

            // Simpan atau Update
            ResponAk04::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $id_sertifikasi], // Kunci pencarian
                $dataSimpan
            );

            // Opsional: Update Status Sertifikasi jadi "banding_diajukan"
            // $sertifikasi->status_sertifikasi = 'banding_diajukan'; // Uncomment jika perlu
            // $sertifikasi->save();

            DB::commit();
            return redirect()->route('tracker')->with('success', 'Pengajuan Banding (AK-04) berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * [MASTER] Menampilkan editor template (Banding Asesmen) per Skema & Jadwal
     */
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.AK.04')
                                    ->first();
        
        $content = $template ? $template->content : [
            'q1' => 'Apakah Proses Banding telah dijelaskan kepada Anda?',
            'q2' => 'Apakah Anda telah mendiskusikan Banding dengan Asesor?',
            'q3' => 'Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?',
            'default_alasan' => ''
        ];

        return view('Admin.master.skema.template.ak04', [
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
            'content' => 'required|array',
            'content.q1' => 'required|string',
            'content.q2' => 'required|string',
            'content.q3' => 'required|string',
            'content.default_alasan' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.AK.04'
            ],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat AK-04 berhasil diperbarui.');
    }

    /**
     * Menampilkan Template Form FR.AK.04 (Admin Master View) - DEPRECATED for management
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

        return view('frontend.FR_AK_04', [
            'sertifikasi' => $sertifikasi,
            'respon' => null,
            'template' => null,
            'isMasterView' => true,
        ]);
    }
}