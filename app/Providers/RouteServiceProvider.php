<?php

namespace App\Providers;

use App\Http\Helpers\ApiResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        date_default_timezone_set(config('app.timezone'));
        
        // نصيحة إضافية: لضمان عمل قاعدة البيانات بنفس التوقيت
        config(['database.connections.mysql.timezone' => '+00:00']);


        RateLimiter::for('otp-limiter', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function (Request $request, array $headers) {
                return ApiResponse::error([
                    'message' => 'لقد تجاوزت عدد المحاولات المسموحة. انتظر قليلاً ثم حاول مجدداً.',
                    'retry_after_seconds' => $headers['Retry-After'] ?? null,
                ], 429);
            });
        });
    }
}
