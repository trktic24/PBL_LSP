<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAsesorApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role->nama_role === 'asesor' && $user->asesor?->status_verifikasi !== 'approved') {
            // tapi kalo lagi di /tunggu-verifikasi, biarin lewat
            if (!$request->is('tunggu-verifikasi')) {
                return redirect()->route('auth.wait');
            }
        }

        return $next($request);
    }

}
