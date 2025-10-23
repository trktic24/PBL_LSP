<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
        base_path('routes/web.php'),
        base_path('routes/webprofil.php') // <-- TAMBAHKAN BARIS INI
    ],
    commands: base_path('routes/console.php'),
    health: '/up',
)
->withMiddleware(function (Middleware $middleware) {
    // ...
})
->withExceptions(function (Exceptions $exceptions) {
    // ...
})->create();
