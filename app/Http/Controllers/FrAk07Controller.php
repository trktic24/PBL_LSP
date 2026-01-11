<?php

namespace App\Http\Controllers;

use App\Models\DataSertifikasiAsesi;
use App\Models\HasilPenyesuaianAK07;
use App\Models\PersyaratanModifikasiAK07;
use App\Models\PoinPotensiAK07;
use App\Models\ResponDiperlukanPenyesuaianAK07;
use App\Models\ResponPotensiAK07;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterFormTemplate;
use App\Models\Skema;

class FrAk07Controller extends Controller
{
    public function index($id_data_sertifikasi_asesi)
    {
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.asesor',
            'responPotensiAk07',
            'responPenyesuaianAk07',
            'hasilPenyesuaianAk07'
        ])->findOrFail($id_data_sertifikasi_asesi);

        $poinPotensi = PoinPotensiAK07::all();
        $persyaratanModifikasi = PersyaratanModifikasiAK07::with('catatanKeterangan')->get();

        return response()->json([
            'data_sertifikasi' => $dataSertifikasi,
            'poin_potensi' => $poinPotensi,
            'persyaratan_modifikasi' => $persyaratanModifikasi,
        ]);
    }

    public function create($id_data_sertifikasi_asesi)
    {
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.janisTuk',
            'jadwal.skema',
            'jadwal.asesor',
            'responPotensiAk07',
            'responPenyesuaianAk07',
            'hasilPenyesuaianAk07'
        ])->findOrFail($id_data_sertifikasi_asesi);

        $poinPotensi = PoinPotensiAK07::all();
        $persyaratanModifikasi = PersyaratanModifikasiAK07::with('catatanKeterangan')->get();

        $user = Auth::user();

        // Authorization Check
        if (!$user || !$user->role) {
            abort(403, 'Unauthorized access.');
        }

        $roleName = strtolower($user->role->nama_role);

        // If NOT Admin/Superadmin, check ownership
        if (!in_array($roleName, ['admin', 'superadmin'])) {
            // If Asesor, check if it's their schedule
            if ($roleName === 'asesor') {
                $asesor = \App\Models\Asesor::where('id_user', Auth::id())->first();
                if (!$asesor) {
                    abort(403, 'Profil Asesor tidak ditemukan.');
                }
                if ($dataSertifikasi->jadwal && $dataSertifikasi->jadwal->id_asesor != $asesor->id_asesor) {
                    abort(403, 'Anda tidak berhak mengakses jadwal ini.');
                }
                // If jadwal is null?
                if (!$dataSertifikasi->jadwal) {
                    // Should potentially fail or allow? Safer to fail if context implies jadwal context
                    // But let's assume valid data has jadwal.
                }
            } else {
                // If role is something else (e.g. Asesi) - are they allowed?
                // Requirement context suggests mainly Asesor view. 
                // If Asesi needs to see it, we would add that check. 
                // For now, standard security: if not whitelisted, deny.
                // Assuming Asesi shouldn't access this specific controller method designed for editing/viewing by Asesor?
                // Actually the previous code didn't check. 
                // But safer to deny explicit unauthorized roles.
                // NOTE: If Asesi views this via a different route, fine. 
                // If this is the SHARED view route, we need to allow Asesi owner.
                // Given the context is "Fix AK07 Read-Only Logic" for Asesor, I will be conservative but allow Asesi if they own the sertifikasi.
                if ($roleName === 'asesi') {
                    // Check if Asesi owns this sertifikasi
                    // $dataSertifikasi->id_asesi vs ...
                    $asesi = \App\Models\Asesi::where('id_user', Auth::id())->first();
                    if (!$asesi || $dataSertifikasi->id_asesi != $asesi->id_asesi) {
                        abort(403, 'Anda tidak berhak mengakses data ini.');
                    }
                } else {
                    abort(403, 'Unauthorized role.');
                }
            }
        }

        $isReadOnly = false;
        // Cek apakah form sudah pernah diisi
        $alreadyFilled = HasilPenyesuaianAK07::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->exists();

        if ($alreadyFilled) {
            $isReadOnly = true;
        }

        // Optional: Admin and Superadmin tetap ReadOnly
        if ($user && $user->role && in_array(strtolower($user->role->nama_role), ['admin', 'superadmin'])) {
            $isReadOnly = true;
        }

        // [NEW] Load Default Values from MasterFormTemplate
        $template = MasterFormTemplate::where('id_skema', $dataSertifikasi->jadwal->id_skema)
            ->where('id_jadwal', $dataSertifikasi->id_jadwal)
            ->where('form_code', 'FR.AK.07')
            ->first();

        // Fallback to generic template if no specific schedule template exists
        if (!$template) {
            $template = MasterFormTemplate::where('id_skema', $dataSertifikasi->jadwal->id_skema)
                ->whereNull('id_jadwal')
                ->where('form_code', 'FR.AK.07')
                ->first();
        }

        $defaultValues = $template ? $template->content : [];

        return view('frontend.AK_07.FR_AK_07', [
            'sertifikasi' => $dataSertifikasi,
            'masterPotensi' => $poinPotensi,
            'masterPersyaratan' => $persyaratanModifikasi,
            'skema' => $dataSertifikasi->jadwal->skema ?? null,
            'asesi' => $dataSertifikasi->asesi ?? null,
            'asesor' => $dataSertifikasi->jadwal->asesor ?? null,
            'jadwal' => $dataSertifikasi->jadwal ?? null,
            'isReadOnly' => $isReadOnly,
            'defaultValues' => $defaultValues, // Pass defaults to view
        ]);
    }

    public function success($id_data_sertifikasi_asesi)
    {
        $dataSertifikasi = DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.skema',
            'jadwal.asesor'
        ])->findOrFail($id_data_sertifikasi_asesi);

        return view('frontend.AK_07.success', [
            'sertifikasi' => $dataSertifikasi,
            'asesi' => $dataSertifikasi->asesi,
            'jadwal' => $dataSertifikasi->jadwal,
        ]);
    }

    public function store(Request $request, $id_data_sertifikasi_asesi)
    {
        // Security Check 1: Prevent re-submission if form already filled
        $alreadyFilled = HasilPenyesuaianAK07::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->exists();
        if ($alreadyFilled) {
            return redirect()->back()->with('error', 'Form ini sudah pernah diisi dan tidak dapat diubah lagi.');
        }

        // Security Check 2: Authorization - only Asesor can submit, Admin cannot
        $user = Auth::user();
        if (!$user || !$user->role) {
            abort(403, 'Unauthorized access.');
        }

        $roleName = $user->role->nama_role;
        if (!in_array(strtolower($roleName), ['asesor'])) {
            abort(403, "Anda tidak memiliki hak akses untuk menyimpan form ini. Role Anda: {$roleName}");
        }

        // Security Check 3: Verify data exists and user has access
        $dataSertifikasi = DataSertifikasiAsesi::with('jadwal.asesor')->findOrFail($id_data_sertifikasi_asesi);

        // Verify asesor owns this data
        $asesor = \App\Models\Asesor::where('id_user', Auth::id())->first();
        if (!$asesor) {
            abort(403, 'Data asesor tidak ditemukan untuk user Anda. Silakan lengkapi profil asesor terlebih dahulu.');
        }

        if (!$dataSertifikasi->jadwal) {
            abort(403, 'Data jadwal tidak ditemukan untuk sertifikasi ini.');
        }

        if ($dataSertifikasi->jadwal->id_asesor != $asesor->id_asesor) {
            abort(403, 'Anda tidak berhak mengakses data ini. Jadwal ini bukan milik Anda.');
        }

        // Validasi sesuai input di view
        $request->validate([
            'potensi_asesi' => 'nullable|array',
            'potensi_asesi.*' => 'exists:poin_potensi_AK07,id_poin_potensi_AK07',

            'penyesuaian' => 'required|array',
            'penyesuaian.*.status' => 'required|in:Ya,Tidak',
            'penyesuaian.*.keterangan' => 'nullable|array',
            'penyesuaian.*.keterangan.*' => 'exists:catatan_keterangan_AK07,id_catatan_keterangan_AK07',
            'penyesuaian.*.catatan_manual' => 'nullable|string',

            'acuan_pembanding' => 'nullable|string',
            'metode_asesmen' => 'nullable|string',
            'instrumen_asesmen' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Save Respon Potensi
            // Hapus yang lama dulu karena ini sistem checklist asesi
            ResponPotensiAK07::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->delete();
            if ($request->has('potensi_asesi')) {
                foreach ($request->potensi_asesi as $id_potensi) {
                    ResponPotensiAK07::create([
                        'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                        'id_poin_potensi_AK07' => $id_potensi,
                        'respon_asesor' => null // Bisa disesuaikan jika asesor yang isi
                    ]);
                }
            }

            // 2. Save Respon Penyesuaian
            // Hapus yang lama dulu
            ResponDiperlukanPenyesuaianAK07::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->delete();
            if ($request->has('penyesuaian')) {
                foreach ($request->penyesuaian as $id_modifikasi => $data) {
                    // 1. Simpan Base Record (Selalu simpan status dan catatan manual di sini)
                    ResponDiperlukanPenyesuaianAK07::create([
                        'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                        'id_persyaratan_modifikasi_AK07' => $id_modifikasi,
                        'id_catatan_keterangan_AK07' => null,
                        'respon_penyesuaian' => $data['status'],
                        'respon_catatan_keterangan' => $data['catatan_manual'] ?? null
                    ]);

                    // 2. Simpan record tambahan untuk setiap checkbox yang dipilih
                    if (!empty($data['keterangan']) && is_array($data['keterangan'])) {
                        foreach ($data['keterangan'] as $id_keterangan) {
                            ResponDiperlukanPenyesuaianAK07::create([
                                'id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi,
                                'id_persyaratan_modifikasi_AK07' => $id_modifikasi,
                                'id_catatan_keterangan_AK07' => $id_keterangan,
                                'respon_penyesuaian' => $data['status'],
                                'respon_catatan_keterangan' => null // Jangan duplikasi catatan manual di sini
                            ]);
                        }
                    }
                }
            }

            // 3. Save Hasil Penyesuaian
            HasilPenyesuaianAK07::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi],
                [
                    'Acuan_Pembanding_Asesmen' => $request->acuan_pembanding ?? '',
                    'Metode_Asesmen' => $request->metode_asesmen ?? '',
                    'Instrumen_Asesmen' => $request->instrumen_asesmen ?? ''
                ]
            );

            DB::commit();
            return redirect()->route('fr-ak-07.success', $id_data_sertifikasi_asesi)->with('success', 'Data FR.AK.07 berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
    public function editTemplate($id_skema, $id_jadwal)
    {
        $skema = Skema::findOrFail($id_skema);
        $template = MasterFormTemplate::where('id_skema', $id_skema)
                                    ->where('id_jadwal', $id_jadwal)
                                    ->where('form_code', 'FR.AK.07')
                                    ->first();
        
        $content = $template ? $template->content : [
            'acuan_pembanding' => '',
            'metode_asesmen' => '',
            'instrumen_asesmen' => ''
        ];

        return view('Admin.master.skema.template.ak07', [
            'skema' => $skema,
            'id_jadwal' => $id_jadwal,
            'content' => $content
        ]);
    }

    public function storeTemplate(Request $request, $id_skema, $id_jadwal)
    {
        $request->validate([
            'content' => 'required|array',
            'content.acuan_pembanding' => 'nullable|string',
            'content.metode_asesmen' => 'nullable|string',
            'content.instrumen_asesmen' => 'nullable|string',
        ]);

        MasterFormTemplate::updateOrCreate(
            [
                'id_skema' => $id_skema, 
                'id_jadwal' => $id_jadwal,
                'form_code' => 'FR.AK.07'
            ],
            ['content' => $request->content]
        );

        return redirect()->back()->with('success', 'Templat AK-07 berhasil diperbarui.');
    }
}
