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
            \Ghi\Domain\Core\Context::class,
            \Ghi\Domain\Core\ContextSession::class
        );

        $this->app->singleton('ghi.context', \Ghi\Domain\Core\ContextSession::class);
    }
}
