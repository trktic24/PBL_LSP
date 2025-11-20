<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function search(Request $request)
    {
        // Ambil query pencarian dari parameter 'q'
        $query = $request->input('q');

        // Kalo query-nya kosong, kembaliin data kosong
        if (!$query) {
            return response()->json([]);
        }

        // Cari negara yang namanya LIKE (mirip)
        $countries = Country::where('name', 'LIKE', "%{$query}%")
                            ->limit(10)
                            ->get(['id', 'name']);

        return response()->json($countries);
    }
}
