<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentFrIa04b;

class AssessmentController extends Controller
{
    public function index()
    {
        // Ambil data terakhir (atau buat kosong jika belum ada)
        // Dalam kasus nyata, ini biasanya berdasarkan ID User atau ID Jadwal
        $data = AssessmentFrIa04b::latest()->first(); 
        
        return view('frontend.FR_IA_04B', compact('data'));
    }

    public function store(Request $request)
    {
        // Simpan atau Update data
        AssessmentFrIa04b::create($request->except('_token'));

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }
}