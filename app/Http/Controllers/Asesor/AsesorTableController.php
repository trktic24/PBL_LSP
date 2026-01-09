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

        // ================================
        // SEARCH GLOBAL
        // ================================
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_regis', 'like', "%{$search}%")
                  ->orWhere('provinsi', 'like', "%{$search}%")
                  ->orWhere('pekerjaan', 'like', "%{$search}%");
            });
        }

        // ================================
        // FILTER PROVINSI
        // ================================
        if ($request->provinsi) {
            $query->whereIn('provinsi', $request->provinsi);
        }

        // ================================
        // FILTER BIDANG / KEAHLIAN
        // ================================
        if ($request->bidang) {
            $query->whereIn('pekerjaan', $request->bidang);
        }

        // ================================
        // PAGINATION (20 per halaman)
        // ================================
        $asesors = $query->paginate(10);

        // ================================
        // DATA FILTER DROPDOWN
        // ================================
        $listProvinsi = Asesor::select('provinsi')
            ->distinct()
            ->orderBy('provinsi')
            ->pluck('provinsi');

        $listBidang = Asesor::select('pekerjaan')
            ->distinct()
            ->orderBy('pekerjaan')
            ->pluck('pekerjaan');

        return view('landing_page.page_info.daftar-asesor', compact(
            'asesors', 'listProvinsi', 'listBidang'
        ));
    }
}
