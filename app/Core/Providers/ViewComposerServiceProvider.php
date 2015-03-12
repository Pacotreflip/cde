<?php  namespace Ghi\Core\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {

    /**
     *
     */
    public function boot()
    {
        View::composer('partials.nav', 'Ghi\Core\Http\Composers\ObraComposer');
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