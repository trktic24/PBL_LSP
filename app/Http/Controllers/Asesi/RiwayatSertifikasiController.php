<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataSertifikasiAsesi;

class RiwayatSertifikasiController extends Controller
{
    /**
     * Tampilkan riwayat sertifikasi asesi.
     */
    public function index()
    {
        $user = Auth::user();

        // Cek apakah user memiliki profil asesi
        if (!$user->asesi) {
            return redirect()->route('asesi.profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum mengakses riwayat sertifikasi.');
        }

        $idAsesi = $user->asesi->id_asesi;

        // Ambil data sertifikasi peserta, termasuk relasi ke jadwal & skema
        $sertifikasiList = DataSertifikasiAsesi::with(['jadwal.skema', 'jadwal.masterTuk'])
                            ->where('id_asesi', $idAsesi)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('asesi.riwayat_sertifikasi', compact('sertifikasiList'));
    }
}
