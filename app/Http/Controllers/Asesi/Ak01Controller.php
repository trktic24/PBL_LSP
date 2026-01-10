<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Support\Facades\Auth;

class Ak01Controller extends Controller
{
    // 1. MENAMPILKAN FORM AK-01
    public function create($id_sertifikasi)
    {
        // Ambil data sertifikasi berdasarkan ID dan pastikan milik user yang login
        $sertifikasi = DataSertifikasiAsesi::with(['jadwal.asesor', 'asesi'])
            ->where('id_asesi', Auth::user()->asesi->id_asesi)
            ->findOrFail($id_sertifikasi);

        $asesi = $sertifikasi->asesi;

        // Kirim variabel $sertifikasi dan $asesi ke View
        return view('frontend.FR_AK_01', compact('sertifikasi', 'asesi'));
    }

    // 2. MENYIMPAN PERSETUJUAN (STORE)
    public function store(Request $request, $id_sertifikasi)
    {
        // Cari data sertifikasi
        $sertifikasi = DataSertifikasiAsesi::findOrFail($id_sertifikasi);

        // Disini logikanya: Update status atau simpan ke tabel khusus AK01
        // Contoh sederhana: Kita anggap AK-01 selesai jika sudah diklik
        // Jika Anda punya tabel 'respon_ak01', tambahkan kode simpannya di sini.
        
        // Contoh update status (opsional, sesuaikan dengan flow Anda)
        // $sertifikasi->status_sertifikasi = 'asesmen_mandiri_selesai'; 
        // $sertifikasi->save();

        return redirect()->route('jadwal.index')->with('success', 'Persetujuan Asesmen (AK-01) berhasil disetujui.');
    }
}