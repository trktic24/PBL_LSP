<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IA09ViewMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $mode = 'view'): Response
    {
        // Set mode ke request untuk digunakan di view
        $request->attributes->set('ia09_mode', $mode);
        
        return $next($request);
    }
}