<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Skema; // Ganti dengan Model yang sesuai
use Illuminate\Http\Request;

class SkemaController extends Controller
{
    /**
     * Tampilkan daftar sumber daya.
     */
    public function index()
    {
        // Ambil semua data dari model
        $data = Skema::all();

        // Kembalikan respons dalam format JSON
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}