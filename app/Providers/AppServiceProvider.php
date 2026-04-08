<?php

namespace App\Providers;

use App\Services\RaceService;
use App\Services\EarthService;
use App\Services\DhakaColoService;
use App\Services\MultiServiceTokenGenerator;
use App\Services\TokenServiceInterface;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TokenServiceInterface::class, function ($app) {
            // Return the MultiServiceTokenGenerator, injecting the three services
            return new MultiServiceTokenGenerator(
                $app->make(RaceService::class),
                $app->make(EarthService::class),
                $app->make(DhakaColoService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('none', function () {
            return Limit::none();
        });
    }
}
