<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asesi;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class AsesiProfileController extends Controller
{
    // Helper function untuk mengambil data asesi
    private function getAsesi($id)
    {
        return Asesi::with(['user', 'dataPekerjaan'])->findOrFail($id);
    }

    public function settings($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        return view('profile_asesi.asesi_profile_settings', compact('asesi'));
    }

    public function form($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        return view('profile_asesi.asesi_profile_form', compact('asesi'));
    }

    public function bukti($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        return view('profile_asesi.asesi_profile_bukti', compact('asesi'));
    }

    public function tracker($id_asesi)
    {
        $asesi = $this->getAsesi($id_asesi);
        return view('profile_asesi.asesi_profile_tracker', compact('asesi'));
    }

}