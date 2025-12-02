<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ia11;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Ia11Controller extends Controller
{
    /**
     * Menampilkan formulir FR.IA.11 berdasarkan ID.
     * 
     */
    public function show($id)
    {
        $ia11 = Ia11::findOrFail($id);
        $user = Auth::user(); 
        
        // Mengambil data JSON dari kolom 'rancangan_produk'
        $asesor_data = $ia11->rancangan_produk ?? [];
        
        $data = [
            'ia11' => $ia11, 
            'user' => $user, 
            'asesor_data' => $asesor_data,
            
            // --- Data Dummy (Ganti dengan relasi yang sebenarnya) ---
            'judul_skema' => 'Web Developer Profesional',
            'nomor_skema' => 'SKM-WD-01',
            'nama_asesor' => 'Budi Santoso', 
            'nama_asesi' => 'Siti Aminah', 
            // Mengambil tanggal dari DB, jika kosong ambil tanggal hari ini
            'tanggal_sekarang' => $ia11->tanggal_pengoperasian ?? Carbon::now()->toDateString(),
        ];

        return view('frontend.FR_IA_11', $data); 
    }

    /**
     * Memperbarui data FR.IA.11. Otorisasi berdasarkan peran (Admin, Asesi, Asesor).
     */
    public function update(Request $request, $id)
    {
        $ia11 = Ia11::findOrFail($id);
        $user = Auth::user();
        $role = $user->role ?? 'guest';

        // 1. Otorisasi Admin (View Only)
        if ($role === 'admin') {
            return back()->with('error', 'Admin hanya memiliki hak lihat (view-only).');
        }

        // Ambil data JSON yang sudah ada dari kolom rancangan_produk
        $rancanganProdukData = $ia11->rancangan_produk ?? [];

        // 2. Data yang dapat diubah oleh ASESI & ASESOR (Kolom DB normal & Data Produk JSON)
        if ($role === 'asesi' || $role === 'asesor') {
            // Kolom DB Normal
            $ia11->nama_produk = $request->input('nama_produk');
            $ia11->standar_industri = $request->input('standar_industri');
            $ia11->tanggal_pengoperasian = $request->input('tanggal_pengoperasian');
            $ia11->gambar_produk = $request->input('gambar_produk');

            // Data Produk yang tersimpan di dalam JSON
            $rancanganProdukData['spesifikasi_umum'] = $request->input('spesifikasi_umum');
            $rancanganProdukData['dimensi_produk'] = $request->input('dimensi_produk');
            $rancanganProdukData['bahan_produk'] = $request->input('bahan_produk');
            $rancanganProdukData['spesifikasi_teknis'] = $request->input('spesifikasi_teknis');
        }

        // 3. Data yang HANYA dapat diubah oleh ASESI (TTD Asesi)
        if ($role === 'asesi') {
            $rancanganProdukData['ttd_asesi'] = $request->input('ttd_asesi');
            
            // Simpan model setelah pembaruan Asesi
            $ia11->rancangan_produk = $rancanganProdukData;
            $ia11->save();
            
            return back()->with('success', 'Formulir FR.IA.11 berhasil diperbarui oleh Asesi.');
        }

        // 4. Data yang HANYA dapat diubah oleh ASESOR (Penilaian, Rekomendasi, TTD Asesor)
        if ($role === 'asesor') {
            // Data Asesmen
            $rancanganProdukData['tuk_type'] = $request->input('tuk_type');
            $rancanganProdukData['tanggal_asesmen'] = $request->input('tanggal_asesmen');

            // Penilaian Checkbox
            $rancanganProdukData['penilaian'] = $this->extractPenilaianData($request); 

            // Rekomendasi & Catatan
            $rancanganProdukData['rekomendasi_kelompok'] = $request->input('rekomendasi_kelompok');
            $rancanganProdukData['rekomendasi_unit'] = $request->input('rekomendasi_unit');
            $rancanganProdukData['catatan_asesor'] = $request->input('catatan_asesor');

            // Tanda Tangan, Penyusun, Validator
            $rancanganProdukData['ttd_asesor'] = $request->input('ttd_asesor');
            
            for ($i = 1; $i <= 2; $i++) {
                $rancanganProdukData["penyusun_nama_{$i}"] = $request->input("penyusun_nama_{$i}");
                $rancanganProdukData["penyusun_nomor_met_{$i}"] = $request->input("penyusun_nomor_met_{$i}");
                $rancanganProdukData["penyusun_ttd_{$i}"] = $request->input("penyusun_ttd_{$i}");
                $rancanganProdukData["validator_nama_{$i}"] = $request->input("validator_nama_{$i}");
                $rancanganProdukData["validator_nomor_met_{$i}"] = $request->input("validator_nomor_met_{$i}");
                $rancanganProdukData["validator_ttd_{$i}"] = $request->input("validator_ttd_{$i}");
            }
            
            // Simpan model setelah pembaruan Asesor
            $ia11->rancangan_produk = $rancanganProdukData;
            $ia11->save();
            return back()->with('success', 'Formulir FR.IA.11 berhasil diperbarui oleh Asesor.');
        }
        
        return back()->with('error', 'Anda tidak memiliki otorisasi untuk mengubah data ini.');
    }
    
    /**
     * Helper function untuk mengumpulkan semua data checklist penilaian dari request.
     */
    protected function extractPenilaianData(Request $request)
    {
        $penilaian = [];
        $fields = [
            'h1a_ya', 'h1a_tidak', 'p1a_ya', 'p1a_tidak',
            'h1b_ya', 'h1b_tidak', 'p1b_ya', 'p1b_tidak',
            'h2a_ya', 'h2a_tidak', 'p2a_ya', 'p2a_tidak',
            'h3a_ya', 'h3a_tidak', 'p3a_ya', 'p3a_tidak',
            'h3b_ya', 'h3b_tidak', 'p3b_ya', 'p3b_tidak',
            'h3c_ya', 'h3c_tidak', 'p3c_ya', 'p3c_tidak',
        ];
        
        foreach ($fields as $field) {
            // Checkbox hanya mengirim nilai jika dicentang. Kita simpan sebagai boolean true/false
            $penilaian[$field] = $request->has($field);
        }

        return $penilaian;
    }
}