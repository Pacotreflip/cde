<?php

namespace Ghi\Providers;

use Ghi\Auth\IntranetUserAuthProvider;
use Ghi\Domain\Core\Usuarios\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class IntranetAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::extend('intranet', function($app) {
            return new IntranetUserAuthProvider(User::class);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
