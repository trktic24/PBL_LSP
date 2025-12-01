<?php

namespace App\Http\Controllers;

use App\Models\FrIa04a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// 1. TAMBAHKAN INI DI ATAS
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FrIa04aController extends Controller
{
    // 2. TAMBAHKAN INI DI DALAM CLASS
    use AuthorizesRequests; 

    // ... function index, store, dll tetap sama ...

    public function store(Request $request)
    {
        // Sekarang kode ini tidak akan error lagi
        $this->authorize('create', FrIa04a::class);
        
        // ... codingan kamu selanjutnya ...
    }

    public function update(Request $request, FrIa04a $frIa04a)
    {
        // Kode ini juga akan aman
        $this->authorize('update', $frIa04a);

        // ... codingan kamu selanjutnya ...
    }
    
    // ...
}   