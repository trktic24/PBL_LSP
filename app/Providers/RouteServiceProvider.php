<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard'; // Kita arahkan ke dashboard Anda

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // INI BAGIAN PENTING YANG HILANG
            // Ini memberi tahu Laravel untuk memuat file routes/api.php
            Route::middleware('api')
                ->prefix('api') // Ini yang memberi prefix /api/
                ->group(base_path('routes/api.php'));

            // Ini memberi tahu Laravel untuk memuat file routes/web.php
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}