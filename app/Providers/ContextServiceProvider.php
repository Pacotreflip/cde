<?php

namespace Ghi\Providers;

use Illuminate\Support\ServiceProvider;

class ContextServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \Ghi\Core\Contracts\Context::class,
            \Ghi\Core\App\ContextSession::class
        );

        $this->app->singleton('ghi.context', \Ghi\Core\App\ContextSession::class);
    }
}
