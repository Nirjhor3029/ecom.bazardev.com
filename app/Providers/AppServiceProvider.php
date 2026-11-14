<?php

namespace App\Providers;

use App\Modules\Notifications\Contracts\SmsProvider;
use App\Modules\Notifications\Drivers\LogSmsProvider;
use App\Modules\Shipping\Contracts\Courier;
use App\Modules\Shipping\Drivers\NullCourier;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ARC-5: swap these bindings for a real driver once SRS §11 open
        // items (SMS provider, courier) are decided — nothing else changes.
        $this->app->bind(SmsProvider::class, LogSmsProvider::class);
        $this->app->bind(Courier::class, NullCourier::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRateLimiters();
    }

    /**
     * SRS 3.5: default rate limits per endpoint group (configurable later via settings).
     */
    protected function registerRateLimiters(): void
    {
        RateLimiter::for('admin-login', fn (Request $r) => Limit::perMinute(5)->by($r->ip().'|'.$r->input('email')));

        RateLimiter::for('otp-send', fn (Request $r) => [
            Limit::perMinutes(10, 3)->by('otp-phone|'.$r->input('phone')),
            Limit::perHour(10)->by('otp-ip|'.$r->ip()),
        ]);

        RateLimiter::for('customer-login', fn (Request $r) => Limit::perMinute(5)->by($r->ip().'|'.$r->input('phone')));

        RateLimiter::for('checkout', fn (Request $r) => Limit::perMinute(10)->by($r->ip()));

        RateLimiter::for('search', fn (Request $r) => Limit::perMinute(60)->by($r->ip()));

        RateLimiter::for('catalog', fn (Request $r) => Limit::perMinute(120)->by($r->ip()));
    }
}
