<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class BeritaAcaraController extends Controller
{
    /**
     * Show Berita Acara View (Preview)
     */
    public function index(Request $request, $id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'tuk', 'asesor'])->findOrFail($id_jadwal);
        
        // Authorization
        $user = Auth::user();
        if ($user->hasRole('asesor') && !$user->hasRole('admin')) {
             if (!$user->asesor || $jadwal->id_asesor != $user->asesor->id_asesor) {
                 abort(403, 'Anda tidak berhak mengakses jadwal ini.');
             }
        }

        // Logic similar to List Asesi but focusing on summary
        $query = DataSertifikasiAsesi::with(['asesi', 'komentarAk05'])
            ->where('id_jadwal', $id_jadwal)
            ->orderBy('id_data_sertifikasi_asesi', 'asc');

        $pendaftar = $query->get();

        $jumlahKompeten = $pendaftar->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'K')->count();
        $jumlahBelumKompeten = $pendaftar->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'BK')->count();

        // Return view (create a view for this if needed, or re-use PDF view for preview?)
        // Usually "Lihat File" might just stream the PDF directly in browser
        // For now, let's stream the PDF for 'index' too if no specific HTML view is requested,
        // OR better, redirect to PDF with 'stream' logic.
        // But the button says "Lihat File", usually implies a page. 
        // Let's return the PDF stream for now as the simplest interpretation of "Lihat File".
        
        return $this->generatePDF($jadwal, $pendaftar, $jumlahKompeten, $jumlahBelumKompeten, 'stream');
    }

    /**
     * Download Berita Acara PDF
     */
    public function cetakPDF($id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'tuk', 'asesor'])->findOrFail($id_jadwal);
        
         // Authorization
         $user = Auth::user();
        if ($user->hasRole('asesor') && !$user->hasRole('admin')) {
             if (!$user->asesor || $jadwal->id_asesor != $user->asesor->id_asesor) {
                 abort(403, 'Anda tidak berhak mengakses dokumen ini.');
             }
        }

        $pendaftar = DataSertifikasiAsesi::with(['asesi', 'komentarAk05'])
            ->where('id_jadwal', $id_jadwal)
            ->orderBy('id_data_sertifikasi_asesi', 'asc')
            ->get();

        $jumlahKompeten = $pendaftar->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'K')->count();
        $jumlahBelumKompeten = $pendaftar->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'BK')->count();

        return $this->generatePDF($jadwal, $pendaftar, $jumlahKompeten, $jumlahBelumKompeten, 'download');
    }

    private function generatePDF($jadwal, $pendaftar, $jumlahKompeten, $jumlahBelumKompeten, $mode)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.berita_acara', [
            'jadwal' => $jadwal,
            'pendaftar' => $pendaftar,
            'jumlahKompeten' => $jumlahKompeten,
            'jumlahBelumKompeten' => $jumlahBelumKompeten,
            'asesor' => $jadwal->asesor
        ])->setPaper('a4', 'portrait');

        $filename = 'Berita_Acara_' . ($jadwal->skema->kode_skema ?? 'Skema') . '.pdf';

        if ($mode === 'download') {
            return $pdf->download($filename);
        } else {
            return $pdf->stream($filename);
        }
    }
}
