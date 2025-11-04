<?php

namespace App\Http\Controllers; 

use App\Models\Asesi; // Pastikan Model Asesi sudah ada
use Illuminate\Http\Request;

class AsesiController extends Controller
{
    /**
     * Menampilkan daftar Asesi.
     */
    public function index()
    {
        // 1. Ambil data Asesi dari database (termasuk relasi user)
        // Jika tabel Asesi kosong atau belum ada, ini akan mengembalikan array kosong [].
        // Kita gunakan data dummy Skema karena relasinya belum kita buat.
        try {
            // Mengambil semua data asesi
            $asesis = Asesi::with('user')->get();
            
            // Memformat data untuk view, menambahkan field skema dummy
            $data_asesi_untuk_view = $asesis->map(function ($asesi) {
                // Skema Dummy, Ganti dengan relasi Skema yang sebenarnya nanti
                $skema = match ($asesi->id_asesi % 3) {
                    1 => 'Teknologi Rekayasa Komputer',
                    2 => 'Sistem Informasi',
                    default => 'Administrasi Perkantoran',
                };
                
                return [
                    'id'                   => $asesi->id_asesi,
                    'nama_lengkap'         => $asesi->nama_lengkap,
                    // Menggunakan null coalescing operator untuk menghindari error jika relasi user belum ada
                    'email'                => $asesi->user->email ?? 'N/A', 
                    'nomor_hp'             => $asesi->nomor_hp,
                    'skema_sertifikasi'    => $skema, 
                ];
            });

        } catch (\Throwable $e) {
            // Jika ada error pada koneksi database atau tabel belum ada, 
            // kita kirim array kosong agar view tetap bisa diakses.
            // Anda bisa log error $e jika perlu.
            $data_asesi_untuk_view = [];
        }


        // 2. Mengirim variabel 'asesis' ke view
        return view('master.asesi.master_asesi', [
            'asesis' => $data_asesi_untuk_view
        ]);
    }
}
