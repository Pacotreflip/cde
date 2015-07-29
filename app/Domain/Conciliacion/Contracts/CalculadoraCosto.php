<?php

namespace Ghi\Domain\Conciliacion\Contracts;

use Carbon\Carbon;

interface CalculadoraCosto
{
    /**
     * Calcula las horas costo que deben proponerse en una conciliacion
     *
     * @param Carbon $fecha_inicial
     * @param Carbon $fecha_final
     * @param $horas_contrato
     * @param $horas_efectivas
     * @param int $horas_reparacion_mayor
     * @param int $horas_reparacion_mayor_cargo_empresa
     */
    public function calcula(
        Carbon $fecha_inicial,
        Carbon $fecha_final,
        $horas_contrato,
        $horas_efectivas,
        $horas_reparacion_mayor = 0,
        $horas_reparacion_mayor_cargo_empresa = 0
    );

    /**
     * @return float
     */
    public function getFactorHorasPorDia();

    /**
     * @return int
     */
    public function getDiasConciliacion();

    /**
     * @return int
     */
    public function getHorasConciliar();

    /**
     * @return int
     */
    public function getHorasPagables();

    /**
     * @return int
     */
    public function getHorasEfectivas();

    /**
     * @return int
     */
    public function getHorasOcio();

    /**
     * @return int
     */
    public function getHorasReparacion();
}
