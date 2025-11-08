<?php

/**
 * File: app/Http/Controllers/FormulirPendaftaran/TandaTanganController.php
 * Ini adalah kode controller FULL FINAL.
 */

namespace App\Http\Controllers\FormulirPendaftaran; // 1. Namespace sesuai folder

// 2. Import semua class yang kita butuhin
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // <-- PENTING: Buat validasi manual
use App\Models\Asesi;

class TandaTanganController extends Controller
{
    /**
     * Method untuk MENAMPILKAN halaman tanda tangan.
     * Ini yang jalan pas lu buka halaman atau di-refresh.
     */
    public function showSignaturePage()
    {
        // "Hack" untuk ngetes: ambil Asesi ID 1
        $id_asesi_hardcoded = 1;

        Log::info('Mencoba mencari Asesi ID 1 DAN data pekerjaannya...');

        // GANTI BARIS INI:
        $asesi = Asesi::with('dataPekerjaan')->find($id_asesi_hardcoded);

        if (!$asesi) {
            Log::error('Gagal menampilkan halaman: Asesi ID 1 tidak ditemukan.');
            abort(404, 'Data Asesi dengan ID 1 tidak ditemukan di database.');
        }

        Log::info('Asesi ID 1 dan relasinya ditemukan. Menampilkan view...');

        return view('formulir pendaftaran.tanda_tangan_pemohon', ['asesi' => $asesi]);
    }

    /**
     * Method untuk MENYIMPAN data tanda tangan (POST) dari form.
     * Ini yang jalan pas lu klik "Selanjutnya".
     */
    public function store(Request $request)
    {
        Log::info('Proses store tanda tangan dimulai...');

        // ----------------------------------------------------
        // 1. VALIDASI MANUAL (Biar bisa redirect ke route)
        // ----------------------------------------------------
        $validator = Validator::make(
            $request->all(),
            [
                'data_tanda_tangan' => 'required|string',
            ],
            [
                // Pesan error custom (biar jelas)
                'data_tanda_tangan.required' => 'File tanda tangan wajib ada. Mohon upload dan klik "Simpan" dulu.',
            ],
        );

        // 2. CEK KALO VALIDASI GAGAL
        if ($validator->fails()) {
            Log::warning('Validasi gagal.', $validator->errors()->toArray());

            // Kalo gagal, KITA REDIRECT KE ROUTE 'show.tandatangan'
            // Ini bakal maksa 'showSignaturePage' jalan lagi & ngirim $asesi
            return redirect()
                ->route('show.tandatangan')
                ->withErrors($validator) // Bawa error-nya
                ->withInput(); // Bawa input lama (meskipun gak kepake)
        }

        Log::info('Validasi sukses. Melanjutkan proses...');

        // 3. Ambil datanya
        $signatureData = $request->input('data_tanda_tangan');
        $dbPath = ''; // Inisialisasi variabel

        // 4. CEK DATANYA: Ini Base64 baru atau path lama?
        if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
            // --- JIKA INI DATA BASE64 BARU (User upload file baru) ---
            Log::info('Mendeteksi data Base64 baru, memproses file...');

            $extension = strtolower($type[1]);
            $base64Data = substr($signatureData, strpos($signatureData, ',') + 1);
            $decodedImage = base64_decode($base64Data);

            if ($decodedImage === false) {
                Log::error('Gagal memproses Base64.');
                return redirect()->route('show.tandatangan')->with('error', 'Gagal memproses gambar. Data Base64 tidak valid.');
            }

            // Hapus file lama kalo ada (BIAR GAK NUMPUK)
            $asesiLama = Asesi::find(1);
            if ($asesiLama && $asesiLama->tanda_tangan && File::exists(public_path($asesiLama->tanda_tangan))) {
                Log::info('Menghapus file tanda tangan lama: ' . $asesiLama->tanda_tangan);
                File::delete(public_path($asesiLama->tanda_tangan));
            }

            $asesiId = 1;
            $fileName = 'ttd_asesi_' . $asesiId . '_' . time() . '.' . $extension;
            $directoryPath = public_path('images/tanda_tangan');
            $filePath = $directoryPath . '/' . $fileName;

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }

            try {
                // Simpen file baru
                File::put($filePath, $decodedImage);
                $dbPath = 'images/tanda_tangan/' . $fileName; // Path BARU
            } catch (\Exception $e) {
                Log::error('Gagal simpan file TTD ke folder: ' . $e->getMessage());
                // Ini error Izin Folder Windows
                return redirect()->route('show.tandatangan')->with('error', 'Gagal menyimpan file gambar di server. (Cek Izin Folder)');
            }
        } else {
            // --- JIKA INI CUMA PATH LAMA (User nggak ganti apa-apa) ---
            Log::info('Mendeteksi path lama, tidak ada file baru yang diproses.');
            $dbPath = $signatureData; // Path LAMA
        }

        // 5. Simpan Path ke Database
        try {
            $asesi = Asesi::find(1);
            if ($asesi) {
                $asesi->tanda_tangan = $dbPath; // Simpen path (baru atau lama)
                $asesi->save();
                Log::info('Path Tanda Tangan berhasil disimpan ke DB: ' . $dbPath);
            } else {
                Log::error('Gagal menyimpan path TTD: Asesi ID 1 tidak ditemukan.');
                return redirect()->route('show.tandatangan')->with('error', 'Data Asesi (ID 1) tidak ditemukan.');
            }
        } catch (\Exception $e) {
            Log::error('Error saat update DB TTD: ' . $e->getMessage());
            return redirect()->route('show.tandatangan')->with('error', 'Gagal menyimpan path tanda tangan ke database.');
        }

        // 6. Redirect Sukses (Ini tetep)
        Log::info('Proses store selesai. Redirect ke form.selesai...');
        return redirect()->route('form.selesai')->with('success', 'Tanda tangan berhasil diunggah!');
    }

    public function storeAjax(Request $request)
    {
        // 1. Validasi manual (mirip kayak sebelumnya)
        $validator = Validator::make($request->all(), [
            'data_tanda_tangan' => 'required|string',
        ]);

        // Kalo gagal (datanya kosong), kirim error JSON
        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tanda tangan tidak boleh kosong.',
                ],
                422,
            ); // 422 = Unprocessable Entity
        }

        $signatureData = $request->input('data_tanda_tangan');

        // 2. Proses data Base64 (kita asumsi PASTI Base64)
        if (preg_match('/^data:image\/(\w+);base64,/', $signatureData, $type)) {
            Log::info('AJAX: Mendeteksi data Base64 baru, memproses file...');

            $extension = strtolower($type[1]);
            $base64Data = substr($signatureData, strpos($signatureData, ',') + 1);
            $decodedImage = base64_decode($base64Data);

            if ($decodedImage === false) {
                return response()->json(['success' => false, 'message' => 'Data Base64 tidak valid.'], 400);
            }

            // Hapus file lama (kalo ada)
            $asesiLama = Asesi::find(1);
            if ($asesiLama && $asesiLama->tanda_tangan && File::exists(public_path($asesiLama->tanda_tangan))) {
                File::delete(public_path($asesiLama->tanda_tangan));
            }

            // Buat file baru
            $asesiId = 1;
            $fileName = 'ttd_asesi_' . $asesiId . '_' . time() . '.' . $extension;
            $directoryPath = public_path('images/tanda_tangan');
            $filePath = $directoryPath . '/' . $fileName;

            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }

            try {
                File::put($filePath, $decodedImage);
            } catch (\Exception $e) {
                Log::error('AJAX: Gagal simpan file TTD ke folder: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan file di server (Izin Folder?).'], 500);
            }

            $dbPath = 'images/tanda_tangan/' . $fileName; // Path BARU
        } else {
            // Kalo datanya bukan Base64
            return response()->json(['success' => false, 'message' => 'Format data tidak valid.'], 400);
        }

        // 3. Simpan Path ke Database
        try {
            $asesi = Asesi::find(1);
            if ($asesi) {
                $asesi->tanda_tangan = $dbPath;
                $asesi->save();
                Log::info('AJAX: Path Tanda Tangan berhasil disimpan ke DB: ' . $dbPath);
            } else {
                return response()->json(['success' => false, 'message' => 'Asesi ID 1 tidak ditemukan.'], 404);
            }
        } catch (\Exception $e) {
            Log::error('AJAX: Error saat update DB TTD: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan ke database.'], 500);
        }

        // 4. Kirim balasan SUKSES (JSON)
        return response()->json([
            'success' => true,
            'message' => 'Tanda tangan berhasil disimpan!',
            'path' => $dbPath, // Kita kirim balik path-nya biar JS bisa update
        ]);
    }
}
