<?php

namespace App\Http\Controllers;

use App\Models\FrIa04a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// 1. TAMBAHKAN INI DI ATAS
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;

class FrIa04aController extends Controller
{
    // 2. TAMBAHKAN INI DI DALAM CLASS
    use AuthorizesRequests; 

    // ... function index, store, dll tetap sama ...

    public function store(Request $request)
    {
        // Sekarang kode ini tidak akan error lagi
        $this->authorize('create', FrIa04a::class);
        
        // ... codingan kamu selanjutnya ...
    }

    public function update(Request $request, FrIa04a $frIa04a)
    {
        // Kode ini juga akan aman
        $this->authorize('update', $frIa04a);

        // ... codingan kamu selanjutnya ...
    }
    
    // ...

    // ... method store, update, dll ...

    /**
     * CETAK PDF FR.IA.04
     */
    public function cetakPDF($id_data_sertifikasi_asesi)
    {
        // 1. Ambil Data Sertifikasi
        $sertifikasi = \App\Models\DataSertifikasiAsesi::with([
            'asesi',
            'jadwal.masterTuk',
            'jadwal.asesor',
            'jadwal.skema',
            'jadwal.skema.kelompokPekerjaan.unitKompetensi' // Untuk header unit
        ])->findOrFail($id_data_sertifikasi_asesi);

        // 2. Ambil Poin (Tugas/Kegiatan)
        $points = \App\Models\PoinIA04A::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get();

        // 3. Ambil Respon (Jawaban/Validasi), keyBy ID Poin biar gampang dipanggil di View
        $responses = \App\Models\ResponIA04A::where('id_data_sertifikasi_asesi', $id_data_sertifikasi_asesi)
            ->get()
            ->keyBy('id_poin_ia04A');

        // 4. Ambil Unit Kompetensi (Untuk Header)
        $units = $sertifikasi->jadwal->skema->kelompokPekerjaan->flatMap->unitKompetensi;

        // 5. Render PDF
        $pdf = Pdf::loadView('pdf.ia_04', [
            'sertifikasi' => $sertifikasi,
            'points' => $points,
            'responses' => $responses,
            'units' => $units,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $namaAsesi = preg_replace('/[^A-Za-z0-9\-]/', '_', $sertifikasi->asesi->nama_lengkap);
        return $pdf->stream('FR_IA_04_' . $namaAsesi . '.pdf');
    }
}   