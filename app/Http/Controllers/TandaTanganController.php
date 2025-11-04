// app/Http/Controllers/TandaTanganController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TandaTanganPemohon; // Pastikan ini ada

class TandaTanganController extends Controller
{
    /**
     * Menyimpan atau mengupdate data tanda tangan asesi.
     */
    public function simpanTandaTangan(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'data_tanda_tangan' => 'required|string', 
        ], [
            'data_tanda_tangan.required' => 'Data tanda tangan wajib diisi. Mohon klik "Simpan" terlebih dahulu.'
        ]);

        // 2. Tentukan ID Asesi
        // >>> UBAH INI dengan cara mendapatkan ID asesi yang sedang login! <<<
        // Contoh placeholder:
        $idAsesi = 101; 
        
        // 3. Simpan atau Update Tanda Tangan
        try {
            TandaTanganPemohon::updateOrCreate(
                ['id_asesi' => $idAsesi], 
                ['data_tanda_tangan' => $request->input('data_tanda_tangan')]
            );

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah database
            return back()->with('error', 'Gagal menyimpan data ke database. Error: ' . $e->getMessage());
        }
            
        // 4. Redirect Sukses
        return redirect('/tunggu_upload_dokumen')->with('success', 'Tanda tangan berhasil disimpan. Lanjut ke proses upload dokumen.');
    }
}