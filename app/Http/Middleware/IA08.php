<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IA08
{
    /**
     * Handle an incoming request.
     *
     * Admin hanya boleh READ-ONLY
     * Asesor mengikuti aturan normal (bisa input jika belum terkunci)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Default: bukan readonly
        $request->merge([
            'is_readonly' => false
        ]);

        // Jika role admin → paksa READ ONLY
        if ($request->is('admin/*')) {
            $request->merge([
                'is_readonly' => true
            ]);
        }

        return $next($request);
    }
}
