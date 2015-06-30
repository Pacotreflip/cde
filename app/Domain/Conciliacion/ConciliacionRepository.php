<?php

namespace Ghi\Domain\Conciliacion;

interface ConciliacionRepository
{
    /**
     * Obtiene una conciliacion por su id
     *
     * @param $id
     * @return Conciliacion
     */
    public function getById($id);

    /**
     * Obtiene las conciliaciones de un almacen
     *
     * @param $idAlmacen
     * @return \Illuminate\Database\Eloquent\Collection|Conciliacion
     */
    public function getByAlmacen($idAlmacen);

    /**
     * Identifica si una conciliacion ya existe dentro de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return bool
     */
    public function existeConciliacionEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * @param Conciliacion $conciliacion
     * @return Conciliacion
     */
    public function save(Conciliacion $conciliacion);
}
