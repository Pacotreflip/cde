<?php namespace Ghi\Conciliacion\Infraestructure;

use Ghi\Conciliacion\Domain\Periodos\PeriodoConciliacion;
use Ghi\Conciliacion\Domain\Periodos\PeriodoRepository;
use Ghi\Conciliacion\Domain\Exceptions\PeriodoYaFueConciliadoException;
use Ghi\Core\App\BaseRepository;

class EloquentPeriodoRepository extends BaseRepository implements PeriodoRepository
{

    /**
     * @param $id
     * @return \Illuminate\Support\Collection|static
     */
    public function findById($id)
    {
        return PeriodoConciliacion::findOrFail($id);
    }

    /**
     * Obtiene las conciliaciones de un equipo
     *
     * @param $idObra
     * @param $idProveedor
     * @param $idEquipo
     * @return mixed
     */
    public function findByEquipo($idObra, $idProveedor, $idEquipo)
    {
        return PeriodoConciliacion::whereIdObra($idObra)
            ->whereIdEmpresa($idProveedor)
            ->whereIdAlmacen($idEquipo)
            ->orderBy('fecha_inicial', 'desc')
            ->get();
    }

    /**
     * @param PeriodoConciliacion $periodo
     * @return mixed
     */
    public function save(PeriodoConciliacion $periodo)
    {
        $periodo->save();

        return $periodo;
    }

    /**
     * @param $idObra
     * @param $idProveedor
     * @param $idEquipo
     * @param $fechaInicial
     * @param $fechaFinal
     * @throws PeriodoYaFueConciliadoException
     * @return mixed
     */
    public function sePuedeConciliar($idObra, $idProveedor, $idEquipo, $fechaInicial, $fechaFinal)
    {
        $periodos = PeriodoConciliacion::whereIdObra($idObra)
            ->whereIdEmpresa($idProveedor)
            ->whereIdAlmacen($idEquipo)
            ->where(function($query) use ($fechaInicial, $fechaFinal)
            {
                $query->where(function($query) use($fechaInicial)
                {
                    $query->where('fecha_inicial', '<=', $fechaInicial)
                    ->where('fecha_final', '>=', $fechaInicial);
                })
                ->orWhere(function($query) use($fechaFinal)
                {
                    $query->where('fecha_inicial', '<=', $fechaFinal)
                        ->where('fecha_final', '>=', $fechaFinal);
                });
            })->count();

        if ($periodos)
        {
            throw new PeriodoYaFueConciliadoException;
        }
    }
}