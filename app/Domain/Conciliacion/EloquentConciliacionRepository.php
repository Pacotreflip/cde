<?php

namespace Ghi\Domain\Conciliacion;

use Ghi\Domain\Core\BaseRepository;

class EloquentConciliacionRepository extends BaseRepository implements ConciliacionRepository
{
    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        return Conciliacion::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getByAlmacen($id_almacen)
    {
        return Conciliacion::where('id_almacen', $id_almacen)
            ->orderBy('fecha_inicial', 'desc')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function existeConciliacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        return Conciliacion::where('id_almacen', $id_almacen)
            ->where(function ($query) use ($fecha_inicial, $fecha_final) {
                $query->where(function ($query) use ($fecha_inicial) {
                    $query->where('fecha_inicial', '<=', $fecha_inicial)
                    ->where('fecha_final', '>=', $fecha_inicial);
                })
                ->orWhere(function ($query) use ($fecha_final) {
                    $query->where('fecha_inicial', '<=', $fecha_final)
                        ->where('fecha_final', '>=', $fecha_final);
                });
            })->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function save(Conciliacion $conciliacion)
    {
        $conciliacion->save();

        return $conciliacion;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Conciliacion $conciliacion)
    {
        $conciliacion->delete();
    }
}
