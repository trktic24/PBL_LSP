<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth; // Hapus atau nonaktifkan ini
use App\Models\Asesi; // Pastikan Anda mengimpor Model Asesi

class PortofolioController extends Controller
{
    public function index()
    {
        // Pilihan 1: Tampilkan data Asesi dengan ID spesifik
        // Ganti angka 1 dengan ID Asesi yang ingin Anda tampilkan
        $asesi = Asesi::find(1); 
        
        // Pilihan 2: Ambil data Asesi PERTAMA di tabel
        // $asesi = Asesi::first();

        if (!$asesi) {
            // Berikan nilai default jika data tidak ditemukan (misalnya tabel kosong)
            $asesi = (object) ['nama_lengkap' => 'Data Asesi Tidak Ditemukan'];
        }

        // Kirim data ke view
        return view('frontend.PORTOFOLIO', compact('asesi'));
    }
}