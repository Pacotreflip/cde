<?php

namespace Ghi\Providers;

use League\Fractal\Manager;
use Illuminate\Support\ServiceProvider;

class FractalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fractal.manager', function () {
            return new Manager();
        });
    }
}
