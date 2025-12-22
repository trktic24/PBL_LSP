<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarin alias middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'check.asesor.approved' => \App\Http\Middleware\CheckAsesorApproved::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            return redirect()->back()->with('error', 'Ukuran total file yang diunggah terlalu besar. Silakan kurangi ukuran file atau kompres dokumen Anda.');
        });
    })
    ->create();
