<?php

namespace App\Http\Controllers;

use App\Models\Banding;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Http\Request;

class BandingController extends Controller
{
    /**
     * Tampilkan form Banding untuk web.
     */
    public function create()
    {
        $dataSertifikasiAsesi = DataSertifikasiAsesi::with('asesi', 'jadwal.asesor', 'jadwal.skema')
            ->latest('id_data_sertifikasi_asesi')
            ->first();

        if (!$dataSertifikasiAsesi) {
            return redirect()->back()->with('error', 'Belum ada data sertifikasi asesi.');
        }

        $namaAsesi = $dataSertifikasiAsesi->asesi->nama_lengkap ?? '-';
        $namaAsesor = $dataSertifikasiAsesi->jadwal->asesor->nama_lengkap ?? '-';
        $noSkema = $dataSertifikasiAsesi->jadwal->skema->kode_unit ?? '-';
        $namaSkema = $dataSertifikasiAsesi->jadwal->skema->nama_skema ?? '-';

        return view('banding', compact('dataSertifikasiAsesi', 'namaAsesi', 'namaAsesor', 'noSkema', 'namaSkema'));
    }

    /**
     * Simpan Banding (Web & API)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'required|integer|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'tuk_sewaktu' => 'nullable|boolean',
            'tuk_tempatkerja' => 'nullable|boolean',
            'tuk_mandiri' => 'nullable|boolean',
            'ya_tidak_1' => 'required|in:Ya,Tidak',
            'ya_tidak_2' => 'required|in:Ya,Tidak',
            'ya_tidak_3' => 'required|in:Ya,Tidak',
            'alasan_banding' => 'required|string',
            'tanggal_pengajuan_banding' => 'nullable|date',
            'tanda_tangan_asesi' => 'nullable|string',
        ]);

        // Checkbox default
        $validated['tuk_sewaktu'] = $request->has('tuk_sewaktu') ? 1 : 0;
        $validated['tuk_tempatkerja'] = $request->has('tuk_tempatkerja') ? 1 : 0;
        $validated['tuk_mandiri'] = $request->has('tuk_mandiri') ? 1 : 0;

        // Tanggal otomatis
        if (empty($validated['tanggal_pengajuan_banding'])) {
            $validated['tanggal_pengajuan_banding'] = now();
        }

        $banding = Banding::create($validated);

        // Jika request API → kembalikan JSON
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Successful',
                'data' => $banding
            ], 201);
        }

        // Jika Web → redirect
        return redirect()->route('terimakasih_banding', ['id' => $banding->id_banding]);
    }

    /**
     * Tampilkan semua Banding (API + Web)
     */
    public function index(Request $request)
    {
        $banding = Banding::with('dataSertifikasiAsesi.asesi')->orderBy('id_banding', 'desc')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Successful',
                'data' => $banding
            ], 200);
        }

        return view('banding_index', compact('banding'));
    }

    /**
     * Tampilkan detail Banding (API + Web)
     */
    public function show(Request $request, $id)
    {
        $banding = Banding::with('dataSertifikasiAsesi.asesi')->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Successful',
                'data' => $banding
            ], 200);
        }

        return view('detail_banding', compact('banding'));
    }

    /**
     * Update Banding (Web & API)
     */
    public function update(Request $request, $id)
    {
        $banding = Banding::findOrFail($id);

        $validated = $request->validate([
            'tuk_sewaktu' => 'nullable|boolean',
            'tuk_tempatkerja' => 'nullable|boolean',
            'tuk_mandiri' => 'nullable|boolean',
            'ya_tidak_1' => 'nullable|in:Ya,Tidak',
            'ya_tidak_2' => 'nullable|in:Ya,Tidak',
            'ya_tidak_3' => 'nullable|in:Ya,Tidak',
            'alasan_banding' => 'nullable|string',
        ]);

        // Assign langsung tanpa has(), supaya false juga tersimpan
        $banding->update([
        'tuk_sewaktu' => $validated['tuk_sewaktu'] ?? $banding->tuk_sewaktu,
        'tuk_tempatkerja' => $validated['tuk_tempatkerja'] ?? $banding->tuk_tempatkerja,
        'tuk_mandiri' => $validated['tuk_mandiri'] ?? $banding->tuk_mandiri,
        'ya_tidak_1' => $validated['ya_tidak_1'] ?? $banding->ya_tidak_1,
        'ya_tidak_2' => $validated['ya_tidak_2'] ?? $banding->ya_tidak_2,
        'ya_tidak_3' => $validated['ya_tidak_3'] ?? $banding->ya_tidak_3,
        'alasan_banding' => $validated['alasan_banding'] ?? $banding->alasan_banding,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Successful',
                'data' => $banding
            ], 200);
        }

        return redirect()->back()->with('success', 'Data banding berhasil diperbarui.');
    }

    /**
     * Hapus Banding (Web & API)
     */
    public function destroy(Request $request, $id)
    {
        $banding = Banding::findOrFail($id);
        $banding->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Successful'
            ], 200);
        }

        return redirect()->back()->with('success', 'Data banding berhasil dihapus.');
    }
}