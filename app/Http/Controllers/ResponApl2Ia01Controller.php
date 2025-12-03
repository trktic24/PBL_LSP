<?php

namespace App\Http\Controllers;

use App\Models\ResponApl2Ia01;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponApl2Ia01Controller extends Controller
{
    /**
     * Tampilkan semua respon milik asesi yang login
     */
    public function index()
    {
        $idAsesi = Auth::user()->id_data_sertifikasi_asesi;

        $respon = ResponApl2Ia01::where('id_data_sertifikasi_asesi', $idAsesi)
            ->with('kriteria')
            ->get();

        return view('respon_apl2.index', compact('respon'));
    }

    /**
     * Tampilkan detail respon tertentu
     */
    public function show($id)
    {
        $idAsesi = Auth::user()->id_data_sertifikasi_asesi;

        $respon = ResponApl2Ia01::where('id_respon_apl2', $id)
            ->where('id_data_sertifikasi_asesi', $idAsesi)
            ->with('kriteria')
            ->firstOrFail();

        return view('respon_apl2.show', compact('respon'));
    }
}