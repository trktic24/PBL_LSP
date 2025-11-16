<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use App\Models\Jadwal; 
use Illuminate\Http\Request;

class SkemaWebController extends Controller
{
    // ...
    
    public function show($id)
    {
        // 1. Ambil data skema
        $skema = Skema::findOrFail($id); 

        // 2. Ambil jadwal terkait DENGAN PERBAIKAN NAMA KOLOM
        $jadwalTerkait = Jadwal::where('id_skema', $id) // <-- INI PERBAIKANNYA!
                                ->where('tanggal_pelaksanaan', '>=', now()) // Gunakan kolom tanggal yang tepat
                                ->orderBy('tanggal_pelaksanaan', 'asc')
                                ->first();

        // 3. Tampilkan view
        return view('landing_page.detail.detail_skema', [
            'skema' => $skema,
            'jadwalTerkait' => $jadwalTerkait 
        ]);
    }
}