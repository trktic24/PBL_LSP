<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use Illuminate\Http\Request;

class AsesorTableController extends Controller
{
    /**
     * Menampilkan daftar asesor dari database.
     * Rute: /daftar-asesor
     */
    public function index(Request $request)
    {
        // Ambil semua data asesor dari tabel 'asesor'
        // Bisa ditambahkan pencarian jika diperlukan
        $query = Asesor::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_regis', 'like', "%{$search}%")
                  ->orWhere('provinsi', 'like', "%{$search}%")
                  ->orWhere('pekerjaan', 'like', "%{$search}%");
        }

        $asesors = $query->get();

        // Kirim data ke view
        return view('landing_page_info.daftar-asesor', compact('asesors'));

    }
}