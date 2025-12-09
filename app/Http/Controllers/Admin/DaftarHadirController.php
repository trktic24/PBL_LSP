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
    public function index(Request $request, $id_jadwal)
    {
        // 1. Ambil Data Jadwal Utama
        $jadwal = Jadwal::with(['skema', 'tuk', 'asesor'])->findOrFail($id_jadwal);

        // 2. Setup Default Sorting
        $sortColumn = $request->input('sort', 'id_data_sertifikasi_asesi');
        $sortDirection = $request->input('direction', 'asc');
        
        // 3. Base Query ke tabel Pivot (DataSertifikasiAsesi)
        $query = DataSertifikasiAsesi::query()
                    ->with(['asesi.user', 'asesi.dataPekerjaan', 'presensi'])
                    ->where('id_jadwal', $id_jadwal)
                    ->select('data_sertifikasi_asesi.*'); // Penting agar ID tidak tertimpa

        // 4. Logic Sorting Lanjutan (Join Table)
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
            // Default: Sort by ID Daftar Hadir
            $query->orderBy('id_data_sertifikasi_asesi', $sortDirection);
        }

        // 5. Search Logic
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

        // 6. Pagination
        $perPage = $request->input('per_page', 10);
        $pendaftar = $query->paginate($perPage)->appends($request->query());

        return view('asesor.daftar_hadir', [
            'jadwal' => $jadwal,
            'pendaftar' => $pendaftar,
            'perPage' => $perPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'role' => Auth::user()->role,
            'mode' => 'view',
        ]);
    }
}