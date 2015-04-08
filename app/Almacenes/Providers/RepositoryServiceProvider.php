<?php namespace Ghi\Almacenes\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
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
			'Ghi\Almacenes\Domain\AlmacenMaquinariaRepository',
			'Ghi\Almacenes\Infraestructure\EloquentAlmacenMaquinariaRepository'
		);

        $this->app->bind(
            'Ghi\Almacenes\Domain\MaterialRepository',
            'Ghi\Almacenes\Infraestructure\EloquentMaterialRepository'
        );
	}

}
