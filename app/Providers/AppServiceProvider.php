<?php

namespace App\Providers;

use App\Auth\HashedRememberTokenUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
        Auth::provider('eloquent-hashed-remember', function ($app, array $config) {
            return new HashedRememberTokenUserProvider($app['hash'], $config['model']);
        });
    }
}
