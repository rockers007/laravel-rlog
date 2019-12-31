<?php

namespace Rockersweb\LaravelRlog;

use Illuminate\Support\ServiceProvider;

class LaravelRlogServiceProvider extends ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel_rlog.php' => config_path('laravel_rlog.php'),
            ], 'laravel-rlog-config');
        }
    }

    /**
     * Make config publishment optional by merge the config from the package.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel_rlog.php',
            'laravel_rlog'
        );
    }
}
