<?php

namespace Ghi\Providers;

use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        setlocale(LC_TIME, 'es_MX.UTF8', 'Spanish_Spain.1252');
    }
}
