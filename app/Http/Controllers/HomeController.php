<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function home(Request $request): View
    {
        return view('frontend/home');
    }
    public function jadwal(Request $request): View
    {
        return view('frontend/jadwal');
    }
    public function laporan(Request $request): View
    {
        return view('frontend/laporan');
    }
    public function profil(Request $request): View
    {
        return view('frontend/profil');
    }
}
