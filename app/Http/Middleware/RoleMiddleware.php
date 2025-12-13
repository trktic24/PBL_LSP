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
     * @param  string ...$roles  // <--- DIUBAH DI SINI
     */
    public function handle(Request $request, Closure $next, ...$roles): Response // <--- DIUBAH DI SINI
    {
        // 1. Cek dulu apakah user sudah login
        if (!Auth::check()) {
            // Jika belum login, lempar ke halaman login
            return redirect('login');
        }

        // 2. Ambil role user yang sedang login
        $userRole = Auth::user()->role->nama_role;

        // 3. Cek apakah role user ada di dalam daftar $roles yang diizinkan
        
        // --- LOGIC TAMBAHAN: Superadmin bisa akses semua fitur Admin ---
        if ($userRole === 'superadmin' && in_array('admin', $roles)) {
            return $next($request);
        }

        if (!in_array($userRole, $roles)) { // <--- LOGIKA UTAMA DIUBAH DI SINI
            // Jika role user TIDAK ADA di dalam array $roles, tolak aksesnya.
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        // 4. Jika role-nya cocok, izinkan request
        return $next($request);
    }
}
