<?php

namespace Ghi\Providers;

use Carbon\Carbon;
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
        View::composer('partials.nav-app', \Ghi\Http\Composers\ObraComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        setlocale(LC_TIME, 'es_MX.UTF8', 'Spanish_Spain.1252');
        Carbon::setLocale('es');

        // if ($this->app->environment() == 'local') {
        //     $this->app->register(\Laracasts\Generators\GeneratorsServiceProvider::class);
        // }

        $this->app->bind(
            \Ghi\Core\Contracts\ObraRepository::class,
            \Ghi\Core\Repositories\EloquentObraRepository::class
        );

        $this->app->bind(
            \Ghi\Core\Contracts\UserRepository::class,
            \Ghi\Core\Repositories\EloquentUserRepository::class
        );
    }
}
