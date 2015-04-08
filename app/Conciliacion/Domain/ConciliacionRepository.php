<?php namespace Ghi\Conciliacion\Domain;

interface ConciliacionRepository
{
    /**
     * Obtiene una conciliacion por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Obtiene las conciliaciones de un almacen
     *
     * @param $idAlmacen
     * @return mixed
     */
    public function getByAlmacen($idAlmacen);

    /**
     * Identifica si una conciliacion ya existe dentro de un periodo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function existeConciliacionEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * @param Conciliacion $periodo
     * @return mixed
     */
    public function save(Conciliacion $periodo);

}