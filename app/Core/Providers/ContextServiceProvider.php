<?php namespace Ghi\Core\Providers;

use Illuminate\Support\ServiceProvider;

class ContextServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Ghi\Core\Services\Context',
            'Ghi\Core\Services\ContextSession'
        );

        $this->app->bindShared('ghi.context', function()
        {
            return $this->app->make('Ghi\Core\Services\ContextSession');
        });

    }
}