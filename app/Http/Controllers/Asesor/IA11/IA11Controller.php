<?php

namespace App\Http\Controllers\Asesor\IA11;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\IA11\IA11;
use App\Models\DataSertifikasiAsesi;

class IA11Controller extends Controller
{
    /**
     * Helper function to process checkbox data from the request.
     * Converts 'ya'/'tidak' pairs into a single boolean.
     */
    private function processCheckbox(Request $request, string $prefix): ?bool
    {
        if ($request->has($prefix . '_ya')) {
            return true;
        }
        if ($request->has($prefix . '_tidak')) {
            return false;
        }
        return null;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_data_sertifikasi_asesi)
    {
        $user = Auth::user();
        $role = $user->role->nama_role ?? 'guest';

        if (!in_array($role, ['admin', 'asesor'])) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Find the IA11 record or create a new instance if it doesn't exist.
        $ia11 = IA11::firstOrNew(['id_data_sertifikasi_asesi' => $id_data_sertifikasi_asesi]);

        // Eager load necessary relationships for header info.
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema', 'jadwal.asesor', 'jadwal.masterTuk', 'jadwal.jenisTuk'])
            ->findOrFail($id_data_sertifikasi_asesi);
        
        $ia11->setRelation('dataSertifikasiAsesi', $sertifikasi);

        // Get TUK type from jenisTuk relationship if ia11->tuk_type is not set
        $tukTypeFromDb = $sertifikasi->jadwal->jenisTuk->jenis_tuk ?? null;
        $currentTukType = $ia11->tuk_type ?? $tukTypeFromDb;

        $data = [
            'ia11' => $ia11,
            'user' => $user,
            'isEditable' => true, // Access is already checked above
            'judul_skema' => $sertifikasi->jadwal->skema->nama_skema ?? 'Skema Tidak Ditemukan',
            'nomor_skema' => $sertifikasi->jadwal->skema->nomor_skema ?? 'Nomor Skema Tidak Ditemukan',
            'nama_asesor' => $sertifikasi->jadwal->asesor->nama_lengkap ?? 'Asesor Tidak Ditemukan',
            'nama_asesi' => $sertifikasi->asesi->nama_lengkap ?? 'Asesi Tidak Ditemukan',
            'tanggal_sekarang' => now()->toDateString(),
            // TUK Data
            'master_tuk' => $sertifikasi->jadwal->masterTuk ?? null,
            'nama_tuk' => $sertifikasi->jadwal->masterTuk->nama_tuk ?? 'TUK Tidak Ditemukan',
            'alamat_tuk' => $sertifikasi->jadwal->masterTuk->alamat ?? '-',
            'jenis_tuk' => $tukTypeFromDb,
            'current_tuk_type' => $currentTukType,
            // Asesor MET Number - using nomor_regis field
            'nomor_met_asesor' => $sertifikasi->jadwal->asesor->nomor_regis ?? '-',
            // Sidebar Data
            'asesi' => $sertifikasi->asesi ?? null,
            'skema' => $sertifikasi->jadwal->skema ?? null,
            'jadwal' => $sertifikasi->jadwal ?? null,
        ];

        return view('frontend.FR_IA_11', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->storeOrUpdate($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_ia11)
    {
        return $this->storeOrUpdate($request, $id_ia11);
    }

    /**
     * Handle both storing and updating the resource.
     */
    private function storeOrUpdate(Request $request, ?string $id_ia11 = null)
    {
        $user = Auth::user();
        $role = $user->role->nama_role ?? 'guest';

        if (!in_array($role, ['admin', 'asesor'])) {
            return back()->with('error', 'Anda tidak memiliki otorisasi untuk menyimpan data ini.');
        }

        // All fields are nullable strings or text, so basic validation is enough.
        $validated = $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'tuk_type' => 'nullable|string',
            'tanggal_asesmen' => 'nullable|date',
            'nama_produk' => 'nullable|string',
            'standar_industri' => 'nullable|string',
            'spesifikasi_umum' => 'nullable|string',
            'dimensi_produk' => 'nullable|string',
            'bahan_produk' => 'nullable|string',
            'spesifikasi_teknis' => 'nullable|string',
            'tanggal_pengoperasian' => 'nullable|date',
            'gambar_produk' => 'nullable|string',
            'rekomendasi_kelompok' => 'nullable|string',
            'rekomendasi_unit' => 'nullable|string',
            'ttd_asesi' => 'nullable|string',
            'ttd_asesor' => 'nullable|string',
            'catatan_asesor' => 'nullable|string',
            'penyusun_nama_1' => 'nullable|string',
            'penyusun_nomor_met_1' => 'nullable|string',
            'penyusun_ttd_1' => 'nullable|string',
            'penyusun_nama_2' => 'nullable|string',
            'penyusun_nomor_met_2' => 'nullable|string',
            'penyusun_ttd_2' => 'nullable|string',
            'validator_nama_1' => 'nullable|string',
            'validator_nomor_met_1' => 'nullable|string',
            'validator_ttd_1' => 'nullable|string',
            'validator_nama_2' => 'nullable|string',
            'validator_nomor_met_2' => 'nullable|string',
            'validator_ttd_2' => 'nullable|string',
        ]);

        // Add checkbox data to the validated data array
        $validated['h1a_hasil'] = $this->processCheckbox($request, 'h1a');
        $validated['p1a_pencapaian'] = $this->processCheckbox($request, 'p1a');
        $validated['h1b_hasil'] = $this->processCheckbox($request, 'h1b');
        $validated['p1b_pencapaian'] = $this->processCheckbox($request, 'p1b');
        $validated['h2a_hasil'] = $this->processCheckbox($request, 'h2a');
        $validated['p2a_pencapaian'] = $this->processCheckbox($request, 'p2a');
        $validated['h3a_hasil'] = $this->processCheckbox($request, 'h3a');
        $validated['p3a_pencapaian'] = $this->processCheckbox($request, 'p3a');
        $validated['h3b_hasil'] = $this->processCheckbox($request, 'h3b');
        $validated['p3b_pencapaian'] = $this->processCheckbox($request, 'p3b');
        $validated['h3c_hasil'] = $this->processCheckbox($request, 'h3c');
        $validated['p3c_pencapaian'] = $this->processCheckbox($request, 'p3c');

        try {
            IA11::updateOrCreate(
                ['id_data_sertifikasi_asesi' => $request->id_data_sertifikasi_asesi],
                $validated
            );

            return redirect()
                ->route('ia11.show', $request->id_data_sertifikasi_asesi)
                ->with('success', 'FR.IA.11 berhasil disimpan.');

        } catch (\Throwable $e) {
            Log::error('IA11 STORE/UPDATE ERROR: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan FR.IA.11: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_ia11)
    {
        // This might need adjustment if id_ia11 is not passed directly anymore
        IA11::findOrFail($id_ia11)->delete();
        return response()->json(['message' => 'FR.IA.11 berhasil dihapus']);
    }

    /**
     * Generate a PDF for the specified resource.
     */
    public function cetakPDF(string $id_data_sertifikasi_asesi)
    {
        $ia11 = IA11::with('dataSertifikasiAsesi.asesi', 'dataSertifikasiAsesi.jadwal.skema')
            ->where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->firstOrFail();

        // Note: The PDF view 'pdf.ia_11' will need to be updated to use the new flat data structure.
        $pdf = Pdf::loadView('pdf.ia_11', compact('ia11'));
        return $pdf->stream('FR_IA_11.pdf');
    }
}
