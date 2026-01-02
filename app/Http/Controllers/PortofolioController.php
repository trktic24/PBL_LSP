<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asesi;
use App\Models\BuktiDasar;

class PortofolioController extends Controller
{
    public function index()
    {
        $sertifikasi = null;
        $nama_skema = 'Belum Ada Skema';
        $nama_lengkap = Auth::user()->name ?? 'Nama Tidak Terdeteksi';
        $dokumen_db = collect();

        if (Auth::check()) {
            $asesi = optional(Auth::user())->asesi;
            
            if ($asesi) {
                $nama_lengkap = $asesi->nama_lengkap;
                $sertifikasi = $asesi->dataSertifikasi()->latest()->first();
                
                if ($sertifikasi) {
                    $dokumen_db = BuktiDasar::where('id_data_sertifikasi_asesi', $sertifikasi->id_data_sertifikasi_asesi)->get();
                }
            }
        }

        if ($sertifikasi && $sertifikasi->jadwal && $sertifikasi->jadwal->skema) {
            $nama_skema = $sertifikasi->jadwal->skema->nama_skema;
        }

        return view('frontend.PORTOFOLIO', compact('asesi', 'nama_lengkap', 'nama_skema', 'dokumen_db'));
    }
}