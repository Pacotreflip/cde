<?php

namespace Ghi\Domain\Conciliacion;

use Ghi\Domain\Conciliacion\Exceptions\YaExisteConciliacionException;
use Ghi\Domain\Core\BaseRepository;

class EloquentConciliacionRepository extends BaseRepository implements ConciliacionRepository
{
    /**
     * Obtiene una conciliacion por su id
     *
     * @param $id
     * @return Conciliacion
     */
    public function getById($id)
    {
        return Conciliacion::findOrFail($id);
    }

    /**
     * Obtiene las conciliaciones de un almacen
     *
     * @param $idAlmacen
     * @return \Illuminate\Database\Eloquent\Collection|Conciliacion
     */
    public function getByAlmacen($idAlmacen)
    {
        return Conciliacion::where('id_almacen', $idAlmacen)
            ->orderBy('fecha_inicial', 'desc')
            ->get();
    }

    /**
     * @param Conciliacion $conciliacion
     * @return Conciliacion
     */
    public function save(Conciliacion $conciliacion)
    {
        $conciliacion->save();

        return $conciliacion;
    }

    /**
     * Identifica si una conciliacion ya existe dentro de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @throws YaExisteConciliacionException
     * @return bool
     */
    public function existeConciliacionEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        return Conciliacion::where('id_almacen', $idAlmacen)
            ->where(function ($query) use ($fechaInicial, $fechaFinal) {
                $query->where(function ($query) use ($fechaInicial) {
                    $query->where('fecha_inicial', '<=', $fechaInicial)
                    ->where('fecha_final', '>=', $fechaInicial);
                })
                ->orWhere(function ($query) use ($fechaFinal) {
                    $query->where('fecha_inicial', '<=', $fechaFinal)
                        ->where('fecha_final', '>=', $fechaFinal);
                });
            })->exists();
    }
}
