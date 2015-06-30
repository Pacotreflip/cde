<?php namespace Ghi\Maquinaria\Domain\Conciliacion\Contracts;

use Ghi\Maquinaria\Domain\Conciliacion\Exceptions\EquipoSinContratoVigenteEnPeriodo;

interface ContratoRentaRepository
{
    /**
     * Obtiene los ids de las ordenes de renta (contratos)
     * de un equipo
     * @param $idObra
     * @param $idProveedor
     * @param $idAlmacen
     * @return mixed
     */
    public function getContratosPorEquipo($idObra, $idProveedor, $idAlmacen);

    /**
     * Obtiene el contrato vigente de un equipo en un periodo
     * @param $idObra
     * @param $idProveedor
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     * @throws EquipoSinContratoVigenteEnPeriodo
     */
    public function getContratoVigenteDeEquipoPorPeriodo($idObra, $idProveedor, $idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene el numero de horas del contrato vigente de un equipo
     * en un periodo
     * @param $idObra
     * @param $idProveedor
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     * @throws EquipoSinContratoVigenteEnPeriodo
     */
    public function getHorasContratoVigenteDeEquipoPorPeriodo($idObra, $idProveedor, $idAlmacen, $fechaInicial, $fechaFinal);
}