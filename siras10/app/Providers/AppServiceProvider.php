<?php

namespace App\Providers;

use App\Auth\CustomEloquentUserProvider;
use Carbon\Carbon;
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
        Carbon::setLocale(config('app.locale'));

        // Registrar el provider personalizado para manejar 'correo' en lugar de 'email'
        Auth::provider('custom-eloquent', function ($app, $config) {
            return new CustomEloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
