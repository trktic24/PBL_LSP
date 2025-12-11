<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\DataSertifikasiAsesi;

use App\Http\Controllers\Controller;

class DaftarHadirController extends Controller
{
    /**
     * Menampilkan Daftar Hadir Peserta untuk Jadwal Tertentu.
     */
    public function daftarHadir(Request $request, $id_jadwal)
    {
        // 1. Ambil Data Jadwal Utama
        $jadwal = Jadwal::with(['skema', 'tuk', 'asesor'])->findOrFail($id_jadwal);

        // 2. Cek Otorisasi HANYA jika role asesor
        if (Auth::user()->role === 'asesor') {
            $asesor = Asesor::where('id_user', Auth::id())->first();

            if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
                abort(403, 'Anda tidak berhak mengakses jadwal ini.');
            }
        }

        // 3. Setup Default Sorting
        $sortColumn = $request->input('sort', 'id_data_sertifikasi_asesi');
        $sortDirection = $request->input('direction', 'asc');
        
        // 4. Base Query ke tabel Pivot (DataSertifikasiAsesi)
        $query = DataSertifikasiAsesi::query()
                    ->with(['asesi.user', 'asesi.dataPekerjaan', 'presensi'])
                    ->where('id_jadwal', $id_jadwal)
                    ->select('data_sertifikasi_asesi.*');

        // 5. Logic Sorting Lanjutan (Join Table)
        if (in_array($sortColumn, ['nama_lengkap', 'alamat_rumah', 'pekerjaan', 'nomor_hp'])) {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                  ->orderBy('asesi.' . $sortColumn, $sortDirection);
        } 
        elseif ($sortColumn == 'institusi') {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                  ->leftJoin('data_pekerjaan_asesi', 'asesi.id_asesi', '=', 'data_pekerjaan_asesi.id_asesi')
                  ->orderBy('data_pekerjaan_asesi.nama_institusi_pekerjaan', $sortDirection);
        } 
        else {
            $query->orderBy('id_data_sertifikasi_asesi', $sortDirection);
        }

        // 6. Search Logic
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->whereHas('asesi', function($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat_rumah', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_hp', 'like', '%' . $searchTerm . '%')
                  ->orWhere('pekerjaan', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('dataPekerjaan', function($q2) use ($searchTerm) {
                      $q2->where('nama_institusi_pekerjaan', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // 7. Pagination
        $perPage = $request->input('per_page', 10);
        $pendaftar = $query->paginate($perPage)->appends($request->query());
        
        // Filter Role
        $mode = Auth::user()->role === 'admin' ? 'view' : 'edit';

        return view('asesor.daftar_hadir',[
            'jadwal' => $jadwal,
            'pendaftar' => $pendaftar,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'role' => Auth::user()->role,
            'mode' => $mode,
        ]);
    }

    public function beritaAcara(Request $request, $id_jadwal)
    {
        // 1. Ambil Data Jadwal Utama
        $jadwal = Jadwal::with(['skema', 'tuk', 'asesor'])->findOrFail($id_jadwal);

        // 2. Cek Otorisasi HANYA jika role asesor
        if (Auth::user()->role === 'asesor') {
            $asesor = Asesor::where('id_user', Auth::id())->first();

            if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
                abort(403, 'Anda tidak berhak mengakses jadwal ini.');
            }
        }

        // 2.5 Cek apakah seluruh AK05 sudah diverifikasi validator
        $adaBelumDiverifikasi = DataSertifikasiAsesi::where('id_jadwal', $id_jadwal)
            ->whereHas('komentarAk05', function ($q) {
                $q->whereNull('verifikasi_validator'); // kolom verifikasi validator
            })
            ->exists();

        if ($adaBelumDiverifikasi) {
            abort(403, 'Berita Acara belum dapat diakses karena hasil asesmen belum diverifikasi.');
        }

        // 3. Setup Default Sorting
        $sortColumn = $request->input('sort', 'id_data_sertifikasi_asesi');
        $sortDirection = $request->input('direction', 'asc');
        
        // 4. Base Query ke tabel Pivot (DataSertifikasiAsesi)
        $query = DataSertifikasiAsesi::query()
                    ->with(['asesi.user', 'asesi.dataPekerjaan', 'presensi', 'komentarAk05'])
                    ->where('id_jadwal', $id_jadwal)
                    ->select('data_sertifikasi_asesi.*');

        // 5. Logic Sorting Lanjutan (Join Table)
        if (in_array($sortColumn, ['nama_lengkap', 'alamat_rumah', 'pekerjaan', 'nomor_hp'])) {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                  ->orderBy('asesi.' . $sortColumn, $sortDirection);
        } 
        elseif ($sortColumn == 'institusi') {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                  ->leftJoin('data_pekerjaan_asesi', 'asesi.id_asesi', '=', 'data_pekerjaan_asesi.id_asesi')
                  ->orderBy('data_pekerjaan_asesi.nama_institusi_pekerjaan', $sortDirection);
        } 
        else {
            $query->orderBy('id_data_sertifikasi_asesi', $sortDirection);
        }

        // 6. Search Logic
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->whereHas('asesi', function($q) use ($searchTerm) {
                $q->where('nama_lengkap', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat_rumah', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_hp', 'like', '%' . $searchTerm . '%')
                  ->orWhere('pekerjaan', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('dataPekerjaan', function($q2) use ($searchTerm) {
                      $q2->where('nama_institusi_pekerjaan', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // 7. Pagination
        $perPage = $request->input('per_page', 10);
        $pendaftar = $query->paginate($perPage)->appends($request->query());

        $jumlahKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'K')
            ->count();

        $jumlahBelumKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'BK')
            ->count();        

        return view('asesor.berita_acara',[
            'asesor' => $asesor,
            'jadwal' => $jadwal,
            'pendaftar' => $pendaftar,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'jumlahKompeten' => $jumlahKompeten,
            'jumlahBelumKompeten' => $jumlahBelumKompeten,
            'role' => Auth::user()->role,
        ]);
    }     

    public function exportPdfdaftarhadir($id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'asesor', 'tuk'])->findOrFail($id_jadwal);
        $pendaftar = DataSertifikasiAsesi::with('asesi.dataPekerjaan', 'presensi')
                        ->where('id_jadwal', $id_jadwal)
                        ->get();

        $pdf = PDF::loadView('pdf.daftar_hadir', compact('jadwal', 'pendaftar'));
        return $pdf->download('daftar_hadir_'.$jadwal->id_jadwal.'.pdf');
    }

    public function exportPdfberitaAcara($id_jadwal)
    {
        $jadwal = Jadwal::with(['skema', 'asesor', 'tuk'])->findOrFail($id_jadwal);
        $pendaftar = DataSertifikasiAsesi::with('asesi.dataPekerjaan', 'presensi', 'komentarAk05')
                        ->where('id_jadwal', $id_jadwal)
                        ->get();

        $jumlahKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'K')
            ->count();

        $jumlahBelumKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'BK')
            ->count();                         

        $pdf = PDF::loadView('pdf.berita_acara', compact('jadwal', 'pendaftar', 'jumlahKompeten', 'jumlahBelumKompeten'));
        return $pdf->download('berita_acara_'.$jadwal->id_jadwal.'.pdf');
    }     
}