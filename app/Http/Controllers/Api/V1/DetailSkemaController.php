<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use Illuminate\Http\Request;

class DetailSkemaController extends Controller
{
    /**
     * Tampilkan detail satu sumber daya Skema menggunakan ID.
     */
    public function show($id) 
    {
        // Mencari data secara eksplisit menggunakan Primary Key 'id_skema'
        // Memuat relasi category yang sudah teruji di web
        $skema = Skema::with(['category'])
            ->where('id_skema', $id)
            ->first();
        
        // Mengembalikan 404 jika data tidak ditemukan
        if (!$skema) {
            return response()->json([
                'status' => 'error',
                'message' => 'Skema tidak ditemukan.'
            ], 404); 
        }

        // Jika Skema ditemukan
        return response()->json([
            'status' => 'success',
            'data' => $skema
        ]);
    }
}