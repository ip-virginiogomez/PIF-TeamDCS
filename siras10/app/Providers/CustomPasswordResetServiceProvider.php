<?php

namespace App\Providers;

use App\Auth\CustomDatabaseTokenRepository;
use App\Auth\CustomEloquentUserProvider;
use App\Auth\CustomPasswordBroker;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Support\ServiceProvider;

class CustomPasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registrar el user provider personalizado
        $this->app->resolving('auth', function ($auth) {
            $auth->provider('custom-eloquent', function ($app, $config) {
                return new CustomEloquentUserProvider($app['hash'], $config['model']);
            });
        });

        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            $config = $app['config']['auth.passwords.users'];

            $repository = new CustomDatabaseTokenRepository(
                $app['db']->connection(),
                $app['hash'],
                $config['table'],
                $app['config']['app.key'],
                $config['expire'],
                $config['throttle'] ?? 0
            );

            // Usar el provider personalizado
            $userProvider = new CustomEloquentUserProvider(
                $app['hash'],
                $app['config']['auth.providers.users.model']
            );

            return new CustomPasswordBroker(
                $repository,
                $userProvider
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
