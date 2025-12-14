<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\DataSertifikasiAsesi;
use App\Models\Asesor;
use Illuminate\Support\Facades\Auth;

class BeritaAcaraController extends Controller
{
    public function beritaAcara(Request $request, $id_jadwal)
    {
        // 1. Ambil jadwal + validasi asesor
        $jadwal = Jadwal::with(['skema', 'tuk', 'asesor'])->findOrFail($id_jadwal);

        $asesor = Asesor::where('id_user', Auth::id())->first();
        if (!$asesor || $jadwal->id_asesor != $asesor->id_asesor) {
            return response()->json([
                'message' => 'Anda tidak berhak mengakses jadwal ini.'
            ], 403);
        }

        // 2. Sorting
        $sortColumn = $request->input('sort', 'id_data_sertifikasi_asesi');
        $sortDirection = $request->input('direction', 'asc');

        $query = DataSertifikasiAsesi::with([
                'asesi.user',
                'asesi.dataPekerjaan',
                'presensi',
                'komentarAk05'
            ])
            ->where('id_jadwal', $id_jadwal);

        // 3. Sorting advanced
        if (in_array($sortColumn, ['nama_lengkap', 'alamat_rumah', 'pekerjaan', 'nomor_hp'])) {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                  ->orderBy('asesi.' . $sortColumn, $sortDirection);
        }
        elseif ($sortColumn === 'institusi') {
            $query->join('asesi', 'data_sertifikasi_asesi.id_asesi', '=', 'asesi.id_asesi')
                  ->leftJoin('data_pekerjaan_asesi', 'asesi.id_asesi', '=', 'data_pekerjaan_asesi.id_asesi')
                  ->orderBy('data_pekerjaan_asesi.nama_institusi_pekerjaan', $sortDirection);
        }
        else {
            $query->orderBy('data_sertifikasi_asesi.id_data_sertifikasi_asesi', $sortDirection);
        }

        // 4. Searching
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('asesi', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('alamat_rumah', 'like', "%$search%")
                  ->orWhere('nomor_hp', 'like', "%$search%")
                  ->orWhere('pekerjaan', 'like', "%$search%")
                  ->orWhereHas('dataPekerjaan', function ($q2) use ($search) {
                      $q2->where('nama_institusi_pekerjaan', 'like', "%$search%");
                  });
            });
        }

        // 5. Pagination
        $perPage = $request->input('per_page', 10);
        $pendaftar = $query->paginate($perPage);

        // 6. Hitung data K / BK
        $jumlahKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'K')
            ->count();

        $jumlahBelumKompeten = $jadwal->dataSertifikasiAsesi
            ->filter(fn($d) => $d->komentarAk05?->rekomendasi === 'BK')
            ->count();

        // 7. Response JSON
        return response()->json([
            'jadwal' => $jadwal,
            'pendaftar' => $pendaftar,
            'stats' => [
                'kompeten' => $jumlahKompeten,
                'belum_kompeten' => $jumlahBelumKompeten,
            ]
        ]);
    }
}