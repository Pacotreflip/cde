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
     * @var
     */
    protected $fecha_inicial;

    /**
     * @var
     */
    protected $fecha_final;

    /**
     * @var
     */
    protected $id_almacen;

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
        $this->id_almacen    = $id_almacen;
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final   = $fecha_final;

        if ($this->repository->existeConciliacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)) {
            throw new YaExisteConciliacionException;
        }

        if (! $this->reporteRepository->existenReportesPorConciliarEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)) {
            throw new NoExisteOperacionAprobadaEnPeriodoException;
        }

        $contrato                = $this->getContratoVigente();
        $horas_contrato          = $contrato->horas_contrato;
        $dias_con_operacion      = $this->reporteRepository->diasConOperacionEnPeriodo($id_almacen, $fecha_inicial, $fecha_final);
        $horas_efectivas         = $this->getHorasEfectivas();
        $horas_reparacion_mayor  = $this->getHorasReparacionMayor();
        $horas_reparacion_mayor_con_cargo = $this->getHorasReparacionMayorConCargo();
        $horas_reparacion_menor  = $this->getHorasReparacionMenor();
        $horas_mantenimiento     = $this->getHorasMantenimiento();
        $horas_ocio              = $this->getHorasOcio();
        $horas_traslado          = $this->getHorasTraslado();
        $horometro_inicial       = $this->reporteRepository->getHorometroIncialPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
        $horometro_final         = $this->reporteRepository->getHorometroFinalPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);
        $horas_horometro         = $this->reporteRepository->getHorasHorometroPorPeriodo($id_almacen, $fecha_inicial, $fecha_final);

        $conciliacion = new Conciliacion(compact('fecha_inicial', 'fecha_final', 'observaciones'));

        $this->calculadora->calcula(
            $conciliacion->fecha_inicial,
            $conciliacion->fecha_final,
            $horas_contrato,
            $horas_efectivas,
            $horas_reparacion_mayor,
            $horas_reparacion_mayor_con_cargo
        );

        $conciliacion->dias_con_operacion           = $dias_con_operacion;
        $conciliacion->horas_contrato               = $horas_contrato;
        $conciliacion->factor_contrato_periodo      = $this->calculadora->getFactorHorasPorDia();
        $conciliacion->horas_a_conciliar            = $this->calculadora->getHorasConciliar();
        $conciliacion->horas_efectivas              = $horas_efectivas;
        $conciliacion->horas_reparacion_mayor       = $horas_reparacion_mayor;
        $conciliacion->horas_reparacion_mayor_con_cargo = $horas_reparacion_mayor_con_cargo;
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

    private function getHorasEfectivas()
    {
        return (int) $this->reporteRepository->sumaHorasEfectivasPorPeriodo($this->id_almacen, $this->fecha_inicial, $this->fecha_final);
    }

    private function getHorasReparacionMayor()
    {
        return (int) $this->reporteRepository->sumaHorasReparacionMayorPorPeriodo($this->id_almacen, $this->fecha_inicial, $this->fecha_final);
    }

    private function getHorasReparacionMayorConCargo()
    {
        return (int) $this->reporteRepository->sumaHorasReparacionMayorPorPeriodo($this->id_almacen, $this->fecha_inicial, $this->fecha_final, true);
    }

    private function getHorasReparacionMenor()
    {
        return (int) $this->reporteRepository->sumaHorasReparacionMenorPorPeriodo($this->id_almacen, $this->fecha_inicial, $this->fecha_final);
    }

    private function getHorasMantenimiento()
    {
        return (int) $this->reporteRepository->sumaHorasMantenimientoPorPeriodo($this->id_almacen, $this->fecha_inicial, $this->fecha_final);
    }

    private function getHorasOcio()
    {
        return (int) $this->reporteRepository->sumaHorasOcioPorPeriodo($this->id_almacen, $this->fecha_inicial, $this->fecha_final);
    }

    private function getHorasTraslado()
    {
        return (int) $this->reporteRepository->sumaHorasTrasladoPorPeriodo($this->id_almacen, $this->fecha_inicial, $this->fecha_final);
    }

    /**
     * Obtiene el registro de las horas mensuales vigentes para un equipo
     *
     * @return mixed
     * @throws ReglaNegocioException
     */
    private function getContratoVigente()
    {
        $numeroContratos = HoraMensual::where('id_almacen', $this->id_almacen)
            ->whereBetween('inicio_vigencia', [$this->fecha_inicial, $this->fecha_final])
            ->count();

        if ($numeroContratos > 1) {
            throw new PeriodoConMultiplesContratosException;
        }

        $contrato = HoraMensual::where('id_almacen', $this->id_almacen)
            ->where('inicio_vigencia', '<=', $this->fecha_inicial)
            ->orWhere('inicio_vigencia', '<=', $this->fecha_final)
            ->orderBy('inicio_vigencia', 'DESC')
            ->first();

        if (! $contrato) {
            throw new SinHorasContratoEnPeriodoException;
        }

        return $contrato;
    }
}
