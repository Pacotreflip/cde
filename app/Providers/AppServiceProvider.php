<?php

namespace Ghi\Providers;

use Ghi\Domain\Conciliacion\CalculadoraCostoDefault;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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
