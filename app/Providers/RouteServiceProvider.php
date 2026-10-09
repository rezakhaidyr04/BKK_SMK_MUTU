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
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limiter for generating CV (resource protection)
        RateLimiter::for('cv-generate', function (Request $request) {
            return Limit::perMinute(3)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limiter for review submission (spam/flood protection)
        RateLimiter::for('submit-review', function (Request $request) {
            return Limit::perDay(3)->by($request->user()?->id ?: $request->ip());
        });

        // Rate limiter for tracer study (sama seperti review: cegah spam update).
        RateLimiter::for('submit-tracer', function (Request $request) {
            return Limit::perDay(3)->by($request->user()?->id ?: $request->ip());
        });

        // B2: kontak publik — longgar tapi anti spam bot.
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // C1: laporan lowongan — cegah spam massal, pola sama seperti review.
        RateLimiter::for('submit-report', function (Request $request) {
            return Limit::perDay(5)->by($request->user()?->id ?: $request->ip());
        });

        // H6: named limiter (kunci independen) untuk broadcast lowongan.
        // JANGAN pakai throttle:3,10 numerik di route ini: route sudah berada
        // dalam grup throttle:60,1 dan limiter numerik berbagi kunci signature
        // yang sama sehingga 1 request memakan 2 hit (uji: limit 3 habis
        // dalam 2 request). Named limiter punya kunci sendiri.
        RateLimiter::for('broadcast', function (Request $request) {
            return Limit::perMinutes(10, 3)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
