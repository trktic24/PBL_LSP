<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  // <-- UBAH INI: dari string $role jadi ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response // <-- UBAH INI
    {
        // 1. Cek dulu apakah user sudah login
        //    (Sebenarnya middleware 'auth' bawaan Breeze sudah nanganin ini,
        //     tapi lebih aman dicek lagi)
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil role user
        $userRole = Auth::user()->role->nama_role;

        // 3. Cek apakah role user ADA DI DALAM daftar role yang diizinkan
        if (! in_array($userRole, $roles)) {
            // Jika tidak ada, lempar 403
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        // 4. Jika ada, izinkan lanjut
        return $next($request);
    }
}
