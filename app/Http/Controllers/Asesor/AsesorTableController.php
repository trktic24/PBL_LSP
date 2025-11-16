<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use Illuminate\Http\Request;

class AsesorTableController extends Controller
{
    public function index(Request $request)
    {
        $query = Asesor::query();

        // ---------------------------
        // SEARCH
        // ---------------------------
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_regis', 'like', "%{$search}%")
                  ->orWhere('provinsi', 'like', "%{$search}%")
                  ->orWhere('pekerjaan', 'like', "%{$search}%");
            });
        }

        // ---------------------------
        // FILTER MULTI PROVINSI
        // ---------------------------
        if ($request->provinsi) {
            $provinsi = is_array($request->provinsi)
                ? $request->provinsi
                : [$request->provinsi];

            $query->whereIn('provinsi', $provinsi);
        }

        // ---------------------------
        // FILTER MULTI BIDANG
        // ---------------------------
        if ($request->bidang) {
            $bidang = is_array($request->bidang)
                ? $request->bidang
                : [$request->bidang];

            $query->whereIn('pekerjaan', $bidang);
        }

        // Ambil hasil filter
        $asesors = $query->get();

        // Dropdown
        $listProvinsi = Asesor::select('provinsi')->distinct()->orderBy('provinsi')->pluck('provinsi');
        $listBidang   = Asesor::select('pekerjaan')->distinct()->orderBy('pekerjaan')->pluck('pekerjaan');

        return view('landing_page.page_info.daftar-asesor', compact(
            'asesors', 'listProvinsi', 'listBidang'
        ));
    }
}
