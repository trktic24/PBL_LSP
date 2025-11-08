<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use Illuminate\Http\Request;

class BelajarController extends Controller
{
    public function index()
    {
        // 2. Minta data ke Koki (Model)
        // "Model Skema, tolong ambilin semua data dari tabel 'skema'"
        // (Pastikan Model Skema lu udah bener $table = 'skema' & $primaryKey = 'id_skema')
        $semua_skema = Skema::all();

        // 3. Oper data itu ke Piring Saji (View)
        // "Tolong kirim data ini ke file 'daftar_skema.blade.php'"
        return view('belajar', [
            'skemas' => $semua_skema 
            // Bikin variabel 'skemas' yg isinya data dari $semua_skema
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'kode_unit' => 'required|string|unique:skema',
            'deskripsi_skema' => 'required|string',
            'SKKNI' => 'required|string',
        ]);

        // 2. Simpen ke database
        // (Ini bisa jalan karena lu udah nambahin $fillable di Model)
        $skemaBaru = Skema::create($validated);

        // 3. Kasih balasan JSON (data baru + status 201 Created)
        return response()->json($skemaBaru, 201);
    }

    /**
     * INI CARA BARU (API) - NGEHASILIN BUNGKUSAN (JSON)
     */
    public function apiIndex()
    {
        // 1. Minta data ke Koki (Model)
        // INI SAMA PERSIS kayak cara lama
        $data_skema = Skema::all();

        // 2. INI BEDANYA!
        // Daripada return view(...), kita return json(...)
        // "Tolong data ini dibungkus jadi JSON, gak usah pake piring"
        return response()->json($data_skema);
    }
    /**
     * U (UPDATE): Ngubah 1 skema SPESIFIK.
     * URL: PUT /api/belajar/{id_skema}
     */
    public function update(Request $request, Skema $skema)
    {
        // 1. Validasi data (mirip 'store', tapi 'unique'-nya beda)
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'kode_unit' => 'required|string|unique:skema,kode_unit,'.$skema->id_skema.',id_skema',
            'deskripsi_skema' => 'required|string',
            'SKKNI' => 'required|string',
        ]);

        // 2. Update datanya
        $skema->update($validated);

        // 3. Kasih balasan JSON (data yang udah di-update)
        return response()->json($skema);
    }

    /**
     * D (DELETE): Ngehapus 1 skema SPESIFIK.
     * URL: DELETE /api/belajar/{id_skema}
     */
    public function destroy(Skema $skema)
    {
        // 1. Hapus datanya
        $skema->delete();

        // 2. Kasih balasan "kosong" (status 204 No Content)
        // Ini artinya "sukses, tapi nggak ada data apa-apa buat ditampilin"
        return response()->json(null, 204);
    }
}
