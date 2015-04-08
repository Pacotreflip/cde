<?php namespace Ghi\Conciliacion\Infraestructure;

use Ghi\Conciliacion\Domain\Conciliacion;
use Ghi\Conciliacion\Domain\ConciliacionRepository;
use Ghi\Conciliacion\Domain\Exceptions\YaExisteConciliacionException;
use Ghi\Core\App\BaseRepository;

class EloquentConciliacionRepository extends BaseRepository implements ConciliacionRepository
{
    /**
     * Obtiene una conciliacion por su id
     *
     * @param $id
     * @return \Illuminate\Support\Collection|static
     */
    public function getById($id)
    {
        return Conciliacion::findOrFail($id);
    }

    /**
     * Obtiene las conciliaciones de un almacen
     *
     * @param $idAlmacen
     * @return mixed
     */
    public function getByAlmacen($idAlmacen)
    {
        return Conciliacion::where('id_obra', $this->context->getId())
            ->where('id_almacen', $idAlmacen)
            ->orderBy('fecha_inicial', 'desc')
            ->get();
    }

    /**
     * @param Conciliacion $periodo
     * @return mixed
     */
    public function save(Conciliacion $periodo)
    {
        $periodo->save();

        return $periodo;
    }

    /**
     * Identifica si una conciliacion ya existe dentro de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @throws YaExisteConciliacionException
     * @return mixed
     */
    public function existeConciliacionEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return Conciliacion::where('id_almacen', $idAlmacen)
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
            })->exists();
    }

}
