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
            'Ghi\Conciliacion\Domain\ProveedorRepository',
            'Ghi\Conciliacion\Infraestructure\EloquentProveedorRepository'
        );

        $this->app->bind(
            'Ghi\Conciliacion\Domain\Periodos\PeriodoRepository',
            'Ghi\Conciliacion\Infraestructure\EloquentPeriodoRepository'
        );

        $this->app->bind(
            'Ghi\Conciliacion\Domain\Rentas\ContratoRentaRepository',
            'Ghi\Conciliacion\Infraestructure\EloquentContratoRentaRepository'
        );
	}

}
