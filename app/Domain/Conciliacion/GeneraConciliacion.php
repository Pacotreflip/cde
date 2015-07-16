<?php

namespace Ghi\Domain\Conciliacion;

use Ghi\Domain\Almacenes\HoraMensual;
use Ghi\Domain\Conciliacion\Exceptions\NoExisteOperacionAprobadaEnPeriodoException;
use Ghi\Domain\Conciliacion\Exceptions\PeriodoConMultiplesContratosException;
use Ghi\Domain\Conciliacion\Exceptions\SinHorasContratoEnPeriodoException;
use Ghi\Domain\Conciliacion\Exceptions\YaExisteConciliacionException;
use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\Domain\ReportesActividad\ReporteActividadRepository;

class GeneraConciliacion
{
    /**
     * @var ReporteActividadRepository
     */
    private $reporteRepository;

    /**
     * @var ConciliacionRepository
     */
    private $repository;

    /**
     * @var CalculadoraCosto
     */
    private $calculadora;

    /**
     * GeneraConciliacion constructor.
     *
     * @param ReporteActividadRepository $reporteRepository
     * @param ConciliacionRepository $repository
     * @param CalculadoraCosto $calculadora
     */
    public function __construct(
        ReporteActividadRepository $reporteRepository,
        ConciliacionRepository $repository,
        CalculadoraCosto $calculadora
    )
    {
        $this->reporteRepository = $reporteRepository;
        $this->repository = $repository;
        $this->calculadora = $calculadora;
    }

    /**
     * Genera una conciliacion de un almacen en un periodo de tiempo
     *
     * @param $id_empresa
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @param string $observaciones
     * @return Conciliacion
     * @throws NoExisteOperacionAprobadaEnPeriodoException
     * @throws ReglaNegocioException
     * @throws YaExisteConciliacionException
     */
    public function generar($id_empresa, $id_almacen, $fecha_inicial, $fecha_final, $observaciones = "")
    {
        if ($this->repository->existeConciliacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)) {
            throw new YaExisteConciliacionException;
        }

        if (! $this->reporteRepository->existenReportesPorConciliarEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)) {
            throw new NoExisteOperacionAprobadaEnPeriodoException;
        }

        $contrato = $this->getContratoVigente($id_almacen, $fecha_inicial, $fecha_final);

        $horas_contrato          = $contrato->horas_contrato;
        $dias_con_operacion      = $this->reporteRepository->diasConOperacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final);
        $horas_efectivas         = $this->getHorasEfectivas($id_almacen, $fecha_inicial, $fecha_final);
        $horas_reparacion_mayor  = $this->getHorasReparacionMayor($id_almacen, $fecha_inicial, $fecha_final);
        $horas_reparacion_menor  = $this->getHorasReparacionMenor($id_almacen, $fecha_inicial, $fecha_final);
        $horas_mantenimiento     = $this->getHorasMantenimiento($id_almacen, $fecha_inicial, $fecha_final);
        $horas_ocio              = $this->getHorasOcio($id_almacen, $fecha_inicial, $fecha_final);
        $horas_traslado          = $this->getHorasTraslado($id_almacen, $fecha_inicial, $fecha_final);
        $horometro_inicial       = $this->reporteRepository->getHorometroIncialPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
        $horometro_final         = $this->reporteRepository->getHorometroFinalPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
        $horas_horometro         = $this->reporteRepository->getHorasHorometroPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

        $conciliacion = new Conciliacion(compact('fecha_inicial', 'fecha_final', 'observaciones'));

        $this->calculadora->calcula(
            $conciliacion->fecha_inicial,
            $conciliacion->fecha_final,
            $horas_contrato,
            $horas_efectivas,
            $horas_reparacion_mayor
        );

        $conciliacion->dias_con_operacion           = $dias_con_operacion;
        $conciliacion->horas_contrato               = $horas_contrato;
        $conciliacion->factor_contrato_periodo      = $this->calculadora->getFactorHorasPorDia();
        $conciliacion->horas_a_conciliar            = $this->calculadora->getHorasConciliar();
        $conciliacion->horas_efectivas              = $horas_efectivas;
        $conciliacion->horas_reparacion_mayor       = $horas_reparacion_mayor;
        $conciliacion->horas_reparacion_menor       = $horas_reparacion_menor;
        $conciliacion->horas_mantenimiento          = $horas_mantenimiento;
        $conciliacion->horas_ocio                   = $horas_ocio;
        $conciliacion->horas_traslado               = $horas_traslado;
        $conciliacion->horometro_inicial            = $horometro_inicial;
        $conciliacion->horometro_final              = $horometro_final;
        $conciliacion->horas_horometro              = $horas_horometro;
        $conciliacion->horas_pagables               = $this->calculadora->getHorasPagables();
        $conciliacion->horas_efectivas_conciliadas  = $horas_efectivas;
        $conciliacion->horas_ocio_conciliadas       = $this->calculadora->getHorasOcio();
        $conciliacion->horas_reparacion_conciliadas = $this->calculadora->getHorasReparacion();
        $conciliacion->id_empresa                   = $id_empresa;
        $conciliacion->id_almacen                   = $id_almacen;
        $usuario                                    = auth()->user()->usuarioCadeco;
        $conciliacion->creadoPor()->associate($usuario);

        return $conciliacion;
    }

    /**
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return int
     */
    private function getHorasEfectivas($id_almacen, $fecha_inicial, $fecha_final)
    {
        return (int) $this->reporteRepository->sumaHorasEfectivasPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
    }

    /**
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return int
     */
    private function getHorasReparacionMayor($id_almacen, $fecha_inicial, $fecha_final)
    {
        return (int) $this->reporteRepository->sumaHorasReparacionMayorPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
    }

    /**
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return int
     */
    private function getHorasReparacionMenor($id_almacen, $fecha_inicial, $fecha_final)
    {
        return (int) $this->reporteRepository->sumaHorasReparacionMenorPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
    }

    /**
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    private function getHorasMantenimiento($id_almacen, $fecha_inicial, $fecha_final)
    {
        return (int) $this->reporteRepository->sumaHorasMantenimientoPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
    }

    /**
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    private function getHorasOcio($id_almacen, $fecha_inicial, $fecha_final)
    {
        return (int) $this->reporteRepository->sumaHorasOcioPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
    }

    /**
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return float
     */
    private function getHorasTraslado($id_almacen, $fecha_inicial, $fecha_final)
    {
        return (int) $this->reporteRepository->sumaHorasTrasladoPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
    }

    /**
     * Obtiene el registro de las horas mensuales vigentes para un equipo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return mixed
     * @throws ReglaNegocioException
     */
    private function getContratoVigente($id_almacen, $fecha_inicial, $fecha_final)
    {
        $numeroContratos = HoraMensual::where('id_almacen', $id_almacen)
            ->whereBetween('inicio_vigencia', [$fecha_inicial, $fecha_final])
            ->count();

        if ($numeroContratos > 1) {
            throw new PeriodoConMultiplesContratosException;
        }

        if ($numeroContratos == 0) {
            throw new SinHorasContratoEnPeriodoException;
        }

        $contrato = HoraMensual::where('id_almacen', $id_almacen)
            ->where('inicio_vigencia', '<=', $fecha_inicial)
            ->orWhere('inicio_vigencia', '<=', $fecha_final)
            ->orderBy('inicio_vigencia', 'DESC')
            ->first();

        return $contrato;
    }
}
