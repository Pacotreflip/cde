<?php namespace Ghi\Core\Providers;

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
			'Ghi\Core\Domain\Almacenes\AlmacenMaquinariaRepository',
			'Ghi\Core\Infraestructure\Almacenes\EloquentAlmacenMaquinariaRepository'
		);

//		$this->app->bind(
//			'Ghi\Core\Domain\Conceptos\Contracts\ConceptoRepository',
//			'Ghi\Core\Infraestructure\Conceptos\EloquentConceptoRepository'
//		);

        $this->app->bind(
            'Ghi\Core\Domain\Obras\ObraRepository',
            'Ghi\Core\Infraestructure\Obras\EloquentObraRepository'
        );

        $this->app->bind(
            'Ghi\Core\Domain\Usuarios\UserRepository',
            'Ghi\Core\Infraestructure\Usuarios\EloquentUserRepository'
        );

//        $this->app->bind(
//            'Ghi\Core\Domain\Usuarios\Contracts\UserSaoRepository',
//            'Ghi\Core\Infraestructure\Usuarios\EloquentUserSaoRepository'
//        );
	}

}
