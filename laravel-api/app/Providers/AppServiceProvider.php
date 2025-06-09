<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    /**
     *  Конфигурация ограничений запросов (rate limiting)
     */
    protected function configureRateLimiting(): void
    {
        // Rate limit for short link creation
        RateLimiter::for('short_links_creation', function (Request $request) {
            $config = Config::get('shortlink.rate_limits.creation');
            return Limit::perMinute($config['max_attempts'])->by($request->ip());
        });

        // Rate limit for short link access
        RateLimiter::for('short_links_access', function (Request $request) {
            $config = Config::get('shortlink.rate_limits.access');
            return Limit::perMinute($config['max_attempts'])->by($request->ip());
        });
    }
}
