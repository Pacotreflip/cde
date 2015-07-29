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
     * @param $id_almacen
     * @return \Illuminate\Database\Eloquent\Collection|Conciliacion
     */
    public function getByAlmacen($id_almacen);

    /**
     * Identifica si una conciliacion ya existe dentro de un periodo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return bool
     */
    public function existeConciliacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Persiste los cambios de una conciliacion
     *
     * @param Conciliacion $conciliacion
     * @return Conciliacion
     */
    public function save(Conciliacion $conciliacion);

    /**
     * Elimina una conciliacion
     *
     * @param Conciliacion $conciliacion
     * @return void
     */
    public function delete(Conciliacion $conciliacion);
}
