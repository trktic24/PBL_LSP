<?php

namespace App\Http\Controllers;

use App\Models\Banding;
use App\Models\Asesmen; // PENTING: Class ini harus ada di app/Models/Asesmen.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BandingAsesmenController extends Controller
{
    /**
     * Menampilkan formulir banding. ID asesmen bersifat opsional.
     * * Jika $id_asesmen adalah null (akses via /banding), form akan tampil kosong.
     * Jika $id_asesmen ada (akses via /banding/1), form akan tampil dengan data asesmen.
     *
     * @param int|null $id_asesmen ID dari Asesmen yang akan diajukan banding
     * @return \Illuminate\Http\Response
     */
    public function create($id_asesmen = null) // Wajib: Tambahkan = null
    {
        try {
            // --- KASUS 1: Akses /banding (ID tidak diberikan) ---
            if (is_null($id_asesmen)) {
                // Buat objek Asesmen kosong (dummy)
                $dataAsesmen = new Asesmen();
                $id_asesi = null; // Asesi juga kosong
                // Langsung tampilkan form
                return view('banding', compact('dataAsesmen', 'id_asesi'));
            }

            // --- KASUS 2: Akses /banding/{id} (ID diberikan) ---
            
            // 1. Cari data asesmen (menggunakan find() agar tidak error jika tidak ada)
            $dataAsesmen = Asesmen::find($id_asesmen);
            $id_asesi = null;

            // 2. Cek apakah asesmen ditemukan
            if ($dataAsesmen) {
                // Jika DITEMUKAN, cek duplikasi banding
                $id_asesi = $dataAsesmen->id_asesi ?? null;
                $banding = Banding::where('id_asesmen', $id_asesmen)->first();

                if ($banding) {
                    return redirect()->route('dashboard')->with('info', 'Pengajuan banding untuk asesmen ini sudah pernah diajukan.');
                }

            } else {
                // 3. Jika ID diberikan tapi data TIDAK DITEMUKAN
                Log::warning('Formulir banding dibuka untuk id_asesmen yang tidak ada: ' . $id_asesmen);
                $dataAsesmen = new Asesmen();
                $dataAsesmen->id_asesmen = $id_asesmen; // Set ID agar hidden input terisi
            }
            
            // Kirim data ke view. 
            return view('banding', compact('dataAsesmen', 'id_asesi'));
            
        } catch (\Exception $e) {
            Log::error('Error loading Banding form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data asesmen untuk banding.');
        }
    }

    /**
     * Menyimpan pengajuan banding yang baru.
     */
    public function store(Request $request)
    {
        // ... (Logika store tetap sama dan akan memastikan 
        // id_asesmen dan id_asesi benar-benar ada di database sebelum disimpan)
        
        // 1. Validasi Data
        $validatedData = $request->validate([
            'id_asesmen' => 'required|exists:asesmen,id_asesmen|unique:banding,id_asesmen', 
            'id_asesi' => 'required|exists:asesi,id_asesi',
            'tuk_sewaktu' => 'nullable',
            'tuk_tempatkerja' => 'nullable',
            'tuk_mandiri' => 'nullable',
            'ya_tidak_1' => 'required|in:Ya,Tidak',
            'ya_tidak_2' => 'required|in:Ya,Tidak',
            'ya_tidak_3' => 'required|in:Ya,Tidak',
            'alasan_banding' => 'required|string|max:5000',
            'tanda_tangan_asesi' => 'required|string',
        ]);
        
        // 2. Proses Penyimpanan
        DB::beginTransaction();
        try {
            
            $dataToCreate = $validatedData;
            $dataToCreate['tuk_sewaktu'] = $request->has('tuk_sewaktu');
            $dataToCreate['tuk_tempatkerja'] = $request->has('tuk_tempatkerja');
            $dataToCreate['tuk_mandiri'] = $request->has('tuk_mandiri');
            $dataToCreate['tanggal_pengajuan_banding'] = now()->toDateString();
            
            Banding::create($dataToCreate);
            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Pengajuan Banding berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack(); 
            Log::error('Error saving Banding: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan pengajuan Banding. Silakan coba lagi.');
        }
    }
}