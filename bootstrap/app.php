<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            base_path('routes/web.php'),
        ],
        api: __DIR__.'/../routes/api.php',
        commands: base_path('routes/console.php'),
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
        //
    })
    ->create();
