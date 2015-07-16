<?php
namespace Ghi\Domain\Conciliacion;

use Carbon\Carbon;

class CalculadoraCosto
{
    const DIAS_POR_MES = 30;

    protected $factor_horas_por_dia;
    protected $dias_conciliacion;
    protected $horas_conciliar;
    protected $horas_pagables;
    protected $horas_efectivas;
    protected $horas_ocio;
    protected $horas_reparacion;

    /**
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
    )
    {
        $this->horas_efectivas   = $horas_efectivas;
        $this->factor_horas_por_dia    = $this->calculaFactorDiario($horas_contrato);
        $this->dias_conciliacion = $this->calculaDiasConciliacion($fecha_inicial, $fecha_final);
        $this->horas_conciliar   = (int) ($this->factor_horas_por_dia * $this->dias_conciliacion);

        $this->horas_pagables = $this->calculaHorasPagables($horas_reparacion_mayor, $horas_reparacion_mayor_cargo_empresa);
        $diferencia_base      = $this->calculaDiferenciaBase();

        if ($diferencia_base > 0) {
            $this->horas_ocio = $diferencia_base;
        }

        if (($this->horas_efectivas + $this->horas_ocio) < $this->horas_conciliar) {
            $this->horas_reparacion = $this->horas_conciliar - ($this->horas_efectivas + $this->horas_ocio);
        }
    }

    /**
     * Calcula el numero de horas pagables
     * @param $horas_reparacion_mayor
     * @param $horas_reparacion_mayor_cargo_empresa
     */
    private function calculaHorasPagables($horas_reparacion_mayor, $horas_reparacion_mayor_cargo_empresa)
    {
        return $this->horas_conciliar - $horas_reparacion_mayor + $horas_reparacion_mayor_cargo_empresa;
    }

    /**
     * Calcula la diferencia entre horas pagables y efectivas
     * para poder determinar si se requiere mas horas para el costo.
     *
     * @return int
     */
    private function calculaDiferenciaBase()
    {
        return $this->horas_pagables - $this->horas_efectivas;
    }

    /**
     * Calcula el factor de horas promedio por dia de las horas de contrato en un mes
     *
     * @param $horas_contrato
     * @return float
     */
    private function calculaFactorDiario($horas_contrato)
    {
        return $horas_contrato / static::DIAS_POR_MES;
    }

    /**
     * Calcula el numero de dias que se estan conciliando en el periodo
     *
     * @param Carbon $fecha_inicial
     * @param Carbon $fecha_final
     * @return int
     */
    private function calculaDiasConciliacion(Carbon $fecha_inicial, Carbon $fecha_final)
    {
        return $fecha_inicial->diffInDays($fecha_final->addDay());
    }

    /**
     * @return mixed
     */
    public function getFactorHorasPorDia()
    {
        return $this->factor_horas_por_dia;
    }

    /**
     * @return mixed
     */
    public function getDiasConciliacion()
    {
        return $this->dias_conciliacion;
    }

    /**
     * @return mixed
     */
    public function getHorasConciliar()
    {
        return $this->horas_conciliar;
    }

    /**
     * @return mixed
     */
    public function getHorasPagables()
    {
        return $this->horas_pagables;
    }

    /**
     * @return mixed
     */
    public function getHorasEfectivas()
    {
        return $this->horas_efectivas;
    }

    /**
     * @return mixed
     */
    public function getHorasOcio()
    {
        return $this->horas_ocio;
    }

    /**
     * @return mixed
     */
    public function getHorasReparacion()
    {
        return $this->horas_reparacion;
    }
}
