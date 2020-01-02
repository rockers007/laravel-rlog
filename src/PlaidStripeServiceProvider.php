<?php

namespace Rockers\PlaidStripe;

use Illuminate\Support\ServiceProvider;

class PlaidStripeServiceProvider extends ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'payment');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/credentials.php' => config_path('credentials.php'),
            ], 'credentials-config');
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
            __DIR__.'/config/credentials.php',
            'credentials'
        );
    }
}
