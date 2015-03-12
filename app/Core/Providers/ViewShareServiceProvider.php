<?php namespace Ghi\Core\Providers;

use Ghi\Core\App\Facades\Context;
use Ghi\Core\Domain\Obras\Obra;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewShareServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
    {
        //
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        App::booted(function()
        {
            View::share('currentUser', Auth::user());
            View::share('currentObra', (Context::getId() ? Obra::find(Context::getId()):null));
        });
	}

}
