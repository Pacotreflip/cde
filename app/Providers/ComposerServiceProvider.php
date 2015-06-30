<?php

namespace Ghi\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Llama a los view composers
     */
    public function boot()
    {
        View::composer('partials.nav', \Ghi\Http\Composers\ObraComposer::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}
