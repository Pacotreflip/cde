<?php namespace Ghi\Conciliacion\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->bindRepositories();
	}

	/**
	 *
     */
	private function bindRepositories()
	{
        $this->app->bind(
            'Ghi\Conciliacion\Domain\ConciliacionRepository',
            'Ghi\Conciliacion\Infraestructure\EloquentConciliacionRepository'
        );

//        $this->app->bind(
//            'Ghi\Conciliacion\Domain\Rentas\ContratoRentaRepository',
//            'Ghi\Conciliacion\Infraestructure\EloquentContratoRentaRepository'
//        );
	}

}
