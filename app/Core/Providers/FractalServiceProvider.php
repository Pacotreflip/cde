<?php namespace Ghi\Core\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class FractalServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('fractal.manager', function()
        {
            return new Manager();
        });
    }

}
