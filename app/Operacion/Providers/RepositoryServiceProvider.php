<?php namespace Ghi\Operacion\Providers;

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
			'Ghi\Operacion\Domain\ReporteActividadRepository',
			'Ghi\Operacion\Infraestructure\EloquentReporteActividadRepository'
		);
	}

}
