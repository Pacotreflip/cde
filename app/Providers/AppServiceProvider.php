<?php

namespace Ghi\Providers;

use Ghi\Domain\Conciliacion\CalculadoraCostoDefault;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('partials.nav', \Ghi\Http\Composers\ObraComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        setlocale(LC_TIME, 'es_MX.UTF8', 'Spanish_Spain.1252');

        if ($this->app->environment() == 'local') {
            $this->app->register(\Laracasts\Generators\GeneratorsServiceProvider::class);
        }

        $this->app->bind(
            \Ghi\Domain\Conciliacion\Contracts\CalculadoraCosto::class,
            \Ghi\Domain\Conciliacion\CalculadoraCostoDefault::class
        );

        $this->app->bind(
            \Ghi\Domain\Conciliacion\Contracts\CalculadoraPartesUso::class,
            \Ghi\Domain\Conciliacion\CalculadoraPartesUsoDefault::class
        );
    }
}
