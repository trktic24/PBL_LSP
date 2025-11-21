<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AsesorController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Asesor Controller OK']);
    }
}