<?php

namespace Ghi\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindCoreRepositories();
        $this->bindAlmacenesRepositories();
        $this->bindReportesActividadRepositories();
        $this->bindConciliacionRepositories();
    }

    /**
     * Core Repositories
     */
    private function bindCoreRepositories()
    {
        $this->app->bind(
            \Ghi\Domain\Core\Usuarios\UserRepository::class,
            \Ghi\Domain\Core\Usuarios\EloquentUserRepository::class
        );

        $this->app->bind(
            \Ghi\Domain\Core\Obras\ObraRepository::class,
            \Ghi\Domain\Core\Obras\EloquentObraRepository::class
        );

        $this->app->bind(
            \Ghi\Domain\Core\Conceptos\ConceptoRepository::class,
            \Ghi\Domain\Core\Conceptos\EloquentConceptoRepository::class
        );

        $this->app->bind(
            \Ghi\Domain\Core\EmpresaRepository::class,
            \Ghi\Domain\Core\EloquentEmpresaRepository::class
        );

        $this->app->bind(
            \Ghi\Domain\Core\MaterialRepository::class,
            \Ghi\Domain\Core\EloquentMaterialRepository::class
        );
    }

    /**
     *
     */
    private function bindAlmacenesRepositories()
    {
        $this->app->bind(
            \Ghi\Domain\Almacenes\AlmacenMaquinariaRepository::class,
            \Ghi\Domain\Almacenes\EloquentAlmacenMaquinariaRepository::class
        );
    }

    /**
     *
     */
    private function bindReportesActividadRepositories()
    {
        $this->app->bind(
            \Ghi\Domain\ReportesActividad\ReporteActividadRepository::class,
            \Ghi\Domain\ReportesActividad\EloquentReporteActividadRepository::class
        );
    }

    /**
     *
     */
    private function bindConciliacionRepositories()
    {
        $this->app->bind(
            \Ghi\Domain\Conciliacion\ConciliacionRepository::class,
            \Ghi\Domain\Conciliacion\EloquentConciliacionRepository::class
        );

//        $this->app->bind(
//            'Ghi\Conciliacion\Domain\Rentas\ContratoRentaRepository',
//            'Ghi\Conciliacion\Infraestructure\EloquentContratoRentaRepository'
//        );
    }
}
