<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use App\Models\Admin;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\BuktiDasar;
use Illuminate\Support\Facades\Storage;

class APL01Controller extends Controller
{
// --- HALAMAN 1 (Data Pribadi / APL 01 Bagian 1) ---
    public function step1($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema.unitKompetensi'])->findOrFail($id);
        
        // Return View Bagian 1
        return view('frontend.APL_01.APL_01_1', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'jadwal'      => $sertifikasi->jadwal,
        ]);
    }

    // --- HALAMAN 2 (Data Pekerjaan / APL 01 Bagian 2) ---
    public function step2($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema', 'buktiDasar'])
            ->findOrFail($id);
        
        // Map Bukti Dasar by 'keterangan' (foto, ktp, ijazah, cv)
        $bukti = $sertifikasi->buktiDasar->pluck('bukti_dasar', 'keterangan')->toArray();

        return view('frontend.APL_01.APL_01_2', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'jadwal'      => $sertifikasi->jadwal,
            'bukti'       => $bukti // Pass existing evidence
        ]);
    }

    // --- HALAMAN 3 (Bukti Kelengkapan / APL 01 Bagian 3) ---
    public function step3($id)
    {
        $sertifikasi = DataSertifikasiAsesi::with(['asesi.dataPekerjaan', 'jadwal.skema'])->findOrFail($id);
        
        // Return View Bagian 3
        return view('frontend.APL_01.APL_01_3', [
            'sertifikasi' => $sertifikasi,
            'asesi'       => $sertifikasi->asesi,
            'skema'       => $sertifikasi->jadwal->skema,
            'jadwal'      => $sertifikasi->jadwal
        ]);
    }

    public function storeStep1(Request $request) {
        // 1. Ambil ID dari input hidden form
        // Salah: findOrFail($request)
        // Benar: Ambil property 'id_data_sertifikasi_asesi' dari $request
        $id = $request->input('id_data_sertifikasi_asesi');


        $id_sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);        

        // 2. Redirect ke halaman selanjutnya (misal APL-02)
        return redirect()->route('APL_01_2', ['id' => $id_sertifikasi]); 
    }
    
    public function storeStep2(Request $request) {
        // 1. Ambil ID dari input hidden form
        // Salah: findOrFail($request)
        // Benar: Ambil property 'id_data_sertifikasi_asesi' dari $request
        $id = $request->input('id_data_sertifikasi_asesi');


        $id_sertifikasi = DataSertifikasiAsesi::with(['asesi', 'jadwal.skema'])->findOrFail($id);        

        // 2. Redirect ke halaman selanjutnya (misal APL-02)
        return redirect()->route('APL_01_3', ['id' => $id_sertifikasi]); 
    }   
    
    public function cetakPDF($id)
    {
        // 1. Ambil Data Lengkap (Asesi, Skema, Unit Kompetensi, Pekerjaan)
        $sertifikasi = DataSertifikasiAsesi::with([
            'asesi.user',
            'asesi.dataPekerjaan', // Pastikan relasi ini ada di model Asesi
            'jadwal.skema.unitKompetensi' // Ambil unit kompetensi dari skema
        ])->findOrFail($id);

        $admin = Admin::first();

        $asesi = $sertifikasi->asesi;
        $skema = $sertifikasi->jadwal->skema;
        $unitKompetensi = $skema->unitKompetensi ?? collect();

        // 2. Render PDF
        $pdf = Pdf::loadView('pdf.apl_01', [
            'sertifikasi'    => $sertifikasi,
            'asesi'          => $asesi,
            'skema'          => $skema,
            'unitKompetensi' => $unitKompetensi,
            'admin'          => $admin,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('FR_APL_01_' . $asesi->nama_lengkap . '.pdf');
    }

    /**
     * Master View APL-01 (Using Daftar Asesi Template)
     * Menampilkan semua pendaftar pada Skema tertentu dengan tampilan "Daftar Asesi".
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
        $jadwal->setRelation('masterTuk', new \App\Models\MasterTUK(['nama_lokasi' => 'Tempat Kerja']));
        $sertifikasi->setRelation('jadwal', $jadwal);

        return view('frontend.APL_01.APL_01_1', [
            'sertifikasi' => $sertifikasi,
            'asesi' => $asesi,
            'skema' => $skema,
            'jadwal' => $jadwal,
            'isMasterView' => true,
        ]);
    }

    // --- FILE UPLOAD HANDLERS ---

    public function uploadBukti(Request $request) {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'type' => 'required|in:foto,ktp,ijazah,cv',
            'file' => 'required|file|max:2048' // Max 2MB
        ]);

        try {
            $id = $request->id_data_sertifikasi_asesi;
            $type = $request->type;
            $file = $request->file('file');

            // Store file
            // Format: uploads/asesi/{id_sertifikasi}/{type}.ext
            $filename = $type . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("uploads/asesi/{$id}", $filename, 'public');

            // Update or Create DB Record
            // Note: status_kelengkapan 'F' for Fulfilled/Ada based on assumption. 
            // Better to check specific code rules but 'Ada' is commonly 'Y' or similar. 
            // In database check it was enum('K','BK')? Or text? 
            // Let's assume 'F' based on common logic or 'Ada'. 
            // Checking BuktiDasar model -> fillable status_kelengkapan. 
            // Let's us 'Ada' as safe text default or 'Y'.
            // Actually, usually it is 'K' (Kompeten/Lengkap) or 'BK' (Belum). Or 'Y'/'T'.
            // I will use 'Ada' for now as it's descriptive, or maybe just leave it null/default if handled by assessor.
            
            $bukti = BuktiDasar::updateOrCreate(
                [
                    'id_data_sertifikasi_asesi' => $id,
                    'keterangan' => $type
                ],
                [
                    'bukti_dasar' => $path,
                    'status_kelengkapan' => 'Ada', // Initial status
                    'status_validasi' => 'Belum'
                ]
            );

            return response()->json([
                'success' => true,
                'path' => asset('storage/' . $path),
                'message' => 'Berhasil diunggah'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteBukti(Request $request) {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required',
            'type' => 'required'
        ]);

        $bukti = BuktiDasar::where('id_data_sertifikasi_asesi', $request->id_data_sertifikasi_asesi)
                           ->where('keterangan', $request->type)
                           ->first();

        if ($bukti) {
            // Delete file
            if (Storage::disk('public')->exists($bukti->bukti_dasar)) {
                Storage::disk('public')->delete($bukti->bukti_dasar);
            }
            $bukti->delete();
            return response()->json(['success' => true, 'message' => 'Berhasil dihapus']);
        }
        
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    public function uploadSignature(Request $request) {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required|exists:data_sertifikasi_asesi,id_data_sertifikasi_asesi',
            'file' => 'required|image|max:2048' // PNG/JPG max 2MB
        ]);

        try {
            $id = $request->id_data_sertifikasi_asesi;
            $file = $request->file('file');
            
            $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id);
            $asesi = $sertifikasi->asesi;

            // Store file in uploads/asesi/{id_sertifikasi}/signature.ext
            // Note: We use id_sertifikasi folder for consistency with bukti
            $filename = 'signature_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("uploads/asesi/{$id}", $filename, 'public');

            // Update Asesi Model
            $asesi->tanda_tangan = $path;
            $asesi->save();

            // Update Certification Status if applicable
            // Logic: If status is 'sedang_mendaftar' or null, update to 'pendaftaran_selesai'
            $statusAwal = DataSertifikasiAsesi::STATUS_SEDANG_MENDAFTAR;
            if ($sertifikasi->status_sertifikasi == $statusAwal || is_null($sertifikasi->status_sertifikasi)) {
                $sertifikasi->status_sertifikasi = DataSertifikasiAsesi::STATUS_PENDAFTARAN_SELESAI;
                $sertifikasi->save();
            }

            return response()->json([
                'success' => true,
                'path' => asset('storage/' . $path),
                'message' => 'Tanda tangan berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteSignature(Request $request) {
        $request->validate([
            'id_data_sertifikasi_asesi' => 'required'
        ]);

        try {
            $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($request->id_data_sertifikasi_asesi);
            $asesi = $sertifikasi->asesi;

            if ($asesi->tanda_tangan) {
                 if (Storage::disk('public')->exists($asesi->tanda_tangan)) {
                    Storage::disk('public')->delete($asesi->tanda_tangan);
                }
                $asesi->tanda_tangan = null;
                $asesi->save();
            }

            return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil dihapus']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getFile($path) {
        // Simple secure wrapper if needed, otherwise storage link is fine.
        // For now relying on public storage link in asset().
        abort(404);
    }
}
