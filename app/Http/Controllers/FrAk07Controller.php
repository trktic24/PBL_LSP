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
            'jadwal.skema',
            'jadwal.asesor',
            'responPotensiAk07',
            'responPenyesuaianAk07',
            'hasilPenyesuaianAk07'
        ])->findOrFail($id_data_sertifikasi_asesi);

        $poinPotensi = PoinPotensiAK07::all();
        $persyaratanModifikasi = PersyaratanModifikasiAK07::with('catatanKeterangan')->get();

        $user = Auth::user();
        $isReadOnly = false;
        // Cek apakah form sudah pernah diisi
        $alreadyFilled = HasilPenyesuaianAK07::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)->exists();

        if ($alreadyFilled) {
            $isReadOnly = true;
        }

        // Optional: Admin and Superadmin tetap ReadOnly
        if ($user && $user->role && in_array($user->role->nama_role, ['admin', 'superadmin'])) {
            $isReadOnly = true;
        }

        return view('frontend.AK_07.FR_AK_07', [
            'sertifikasi' => $dataSertifikasi,
            'masterPotensi' => $poinPotensi,
            'masterPersyaratan' => $persyaratanModifikasi,
            'skema' => $dataSertifikasi->jadwal->skema ?? null,
            'asesi' => $dataSertifikasi->asesi ?? null,
            'asesor' => $dataSertifikasi->jadwal->asesor ?? null,
            'jadwal' => $dataSertifikasi->jadwal ?? null,
            'isReadOnly' => $isReadOnly,
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
        if (!in_array($roleName, ['asesor'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk menyimpan form ini.');
        }

        // Security Check 3: Verify data exists and user has access
        $dataSertifikasi = DataSertifikasiAsesi::with('jadwal.asesor')->findOrFail($id_data_sertifikasi_asesi);

        // Verify asesor owns this data
        $asesor = \App\Models\Asesor::where('id_user', $user->id)->first();
        if (!$asesor || $dataSertifikasi->jadwal->id_asesor != $asesor->id_asesor) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
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
}
