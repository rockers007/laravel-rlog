<?php

namespace Rockersweb\LaravelRlog\Test;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return ['Rockersweb\LaravelRlog\LaravelRlogServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('logging.channels.stack.tap', [\Rockersweb\LaravelRlog\Rlog::class]);
    }
}
