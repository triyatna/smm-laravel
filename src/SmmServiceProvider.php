<?php

namespace Triyatna\SmmLaravel;

use Illuminate\Support\ServiceProvider;
use Triyatna\SmmLaravel\Console\InstallCommand;

class SmmServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/smm-api.php', 'smm');

        $this->app->singleton('smm', function ($app) {
            return new Smm(
                $app['config']['smm']['api_url'],
                $app['config']['smm']['api_id'],
                $app['config']['smm']['api_key']
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/smm-api.php' => config_path('smm.php'),
            ], 'smm-config');

            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
