<?php

namespace App\Providers;

use App\Core\Domain\Interfaces\OrderRepositoryInterface;
use App\Core\Domain\Interfaces\PaymentGatewayInterface;
use App\Core\Domain\Interfaces\SanctumRepositoryInterface;
use App\Core\Domain\Interfaces\JwtAuthRepositoryInterface;
use App\Models\Order;
use App\Models\User;
use App\Repositories\JwtAuthRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SanctumRepository;
use App\Services\StripeService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider; 


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayInterface::class, function ($app) {
            return new StripeService(env('STRIPE_KEY'), $app->make(OrderRepositoryInterface::class));
        });

        $this->app->bind(OrderRepositoryInterface::class, function ($app) {
            return new OrderRepository($app->make(Order::class));
        });

        $this->app->bind(SanctumRepositoryInterface::class, function ($app) {
            return new SanctumRepository($app->make(User::class));
        });

        $this->app->bind(JwtAuthRepositoryInterface::class, function ($app) {
            return new JwtAuthRepository($app->make(User::class));
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('Products', function (Request $request) {
            return $request->user() ?
                Limit::perMinute(10)->by($request->ip())
                : Limit::perMinute(5)->by($request->ip());
        });

    }
}
