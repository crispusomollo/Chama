<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // Programmatically bind cloud database parameters at runtime for Render container safety
        if (env('APP_ENV') === 'production') {
            Config::set('database.connections.pgsql.host', 'dpg-d8ej1ekm0tmc73etdnqg-a.oregon-postgres.render.com'); // Put your public external host domain here
            Config::set('database.connections.pgsql.database', 'chama_ch2c');
            Config::set('database.connections.pgsql.username', 'chama_ch2c_user');
            Config::set('database.connections.pgsql.password', 'LnAD1NttGmXHP6vwd4tQFh5zQGGA2B3p');
        }
    }
}
