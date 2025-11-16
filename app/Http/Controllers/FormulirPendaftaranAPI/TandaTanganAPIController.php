<?php

namespace App\Http\Controllers\FormulirPendaftaranAPI;

use App\Models\Asesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Resources\AsesiResource;
use App\Http\Resources\AsesiTTDResource;
use Illuminate\Support\Facades\Validator;

class TandaTanganAPIController extends Controller
{
    /**
     * METHOD API 1: AMBIL DATA ASESI
     * (Ini udah bener, gak perlu diubah)
     */
    public function index()
    {
        $asesis = Asesi::all();
        return AsesiResource::collection($asesis);
    }

    public function show($id_asesi)
    {
        $asesi = Asesi::with('dataPekerjaan:id_pekerjaan,id_asesi,jabatan,nama_institusi_pekerjaan,alamat_institusi')->findOrFail($id_asesi);
        return new AsesiTTDResource($asesi);
    }
}