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
}
