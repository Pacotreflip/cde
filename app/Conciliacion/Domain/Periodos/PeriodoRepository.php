<?php namespace Ghi\Conciliacion\Domain\Periodos;

interface PeriodoRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Obtiene las conciliaciones de un equipo
     * @param $idObra
     * @param $idProveedor
     * @param $idEquipo
     * @return mixed
     */
    public function findByEquipo($idObra, $idProveedor, $idEquipo);

    /**
     * @param $idObra
     * @param $idProveedor
     * @param $idEquipo
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     */
    public function sePuedeConciliar($idObra, $idProveedor, $idEquipo, $fechaInicial, $fechaFinal);

    /**
     * @param PeriodoConciliacion $periodo
     * @return mixed
     */
    public function save(PeriodoConciliacion $periodo);
}