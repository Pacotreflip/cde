<?php

namespace Ghi\Domain\Conciliacion;

use Ghi\Domain\Conciliacion\Contracts\CalculadoraPartesUso;
use Ghi\Domain\ReportesActividad\ReporteActividad;
use Ghi\Domain\ReportesActividad\ReporteActividadRepository;
use Ghi\Domain\ReportesActividad\TipoHora;

class CalculadoraPartesUsoDefault implements CalculadoraPartesUso
{
    /**
     * @var ReporteActividadRepository
     */
    private $reporteRepository;

    /**
     * @var Conciliacion
     */
    protected $conciliacion;

    /**
     * @var int
     */
    protected $horasEfectivas = 0;

    /**
     * @var int
     */
    protected $horasOcio = 0;

    /**
     * @var int
     */
    protected $horasReparacion = 0;

    /**
     * @var int
     */
    protected $ocioPorDia = 0;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $partesUso;

    /**
     * @param ReporteActividadRepository $reporteRepository
     */
    public function __construct(ReporteActividadRepository $reporteRepository)
    {
        $this->reporteRepository = $reporteRepository;
    }

    /**
     * @param Conciliacion $conciliacion
     * @return \Illuminate\Support\Collection
     */
    public function calcula(Conciliacion $conciliacion)
    {
        $this->conciliacion    = $conciliacion;
        $this->horasEfectivas  = $this->conciliacion->horas_efectivas_conciliadas;
        $this->horasOcio       = $this->conciliacion->horas_ocio_conciliadas;
        $this->horasReparacion = $this->conciliacion->horas_reparacion_conciliadas;
        $this->partesUso       = collect();

        // Las horas efectivas seran como maximo las capturadas en el periodo cuando las conciliadas sean mas
        if ($this->horasEfectivas > $this->conciliacion->horas_efectivas) {
            $this->horas_efectivas = $this->conciliacion->horas_efectivas;
        }

        $this->ocioPorDia = $this->calculaOcioPorDia();

        // Genera la distribucion de las horas de acuerdo a los reportes capturados y las horas conciliadas
        foreach ($this->getReportes() as $reporte) {
            $parteUso = $this->generaDistribucion($reporte);
            $this->partesUso->push($parteUso);
        }

        return $this->partesUso;
    }

    /**
     * Calcula el ocio por dia que se debe distribuir
     *
     * @return float|int
     */
    private function calculaOcioPorDia()
    {
        $ocioPorDistribuir = $this->horasOcio - $this->conciliacion->horas_ocio;

        if ($ocioPorDistribuir > 0) {
            return ($ocioPorDistribuir / $this->conciliacion->dias_con_operacion);
        }
        return 0;
    }
    /**
     * Obtiene los reportes de actividad del periodo conciliado
     *
     * @return Collection
     */
    private function getReportes()
    {
        return $this->reporteRepository->getAprobados(
            $this->conciliacion->id_almacen,
            $this->conciliacion->fecha_inicial,
            $this->conciliacion->fecha_final
        );
    }

    /**
     * Genera la distribucion de horas que tendra este reporte de actividades
     *
     * @param ReporteActividad $reporte
     * @return array
     */
    private function generaDistribucion(ReporteActividad $reporte)
    {
        $parteUso = [
            'fecha'         => $reporte->fecha->format('Y-m-d'),
            'observaciones' => $reporte->observaciones,
            'horas'         => [],
        ];

        $horas = $this->getHorasEfectivas($reporte);

        if ($this->horasOcio > 0) {
            $horas[] = $this->generaHorasOcio($reporte);
        }

        if ($this->horasReparacion > 0) {
            if ($reporte->tieneHorasReparacionMayor()) {
                $horas[] = $this->generaHorasReparacion($reporte);
            }
        }

        $parteUso['horas'] = $horas;

        return $parteUso;
    }

    /**
     * Obtiene las horas efectivas por concepto de un reporte
     *
     * @param ReporteActividad $reporte
     * @return array
     */
    private function getHorasEfectivas(ReporteActividad $reporte)
    {
        $horas = [];

        $efectivas = $reporte->actividades()->select('id_concepto', \DB::raw('SUM(cantidad) as cantidad'))
            ->where('tipo_hora', TipoHora::EFECTIVA)
            ->groupBy('id_concepto')
            ->lists('cantidad', 'id_concepto');

        // Si las horas estan en diferentes conceptos
        foreach ($efectivas as $id_concepto => $cantidad) {
            $horas[] = ['tipo' => TipoHora::EFECTIVA, 'id_concepto' => $id_concepto, 'cantidad' => $cantidad];
            // Resta estas horas al total a distribuir
            $this->horasEfectivas -= $cantidad;
        }

        return $horas;
    }

    /**
     * Obtiene las horas de reparacion mayor de un reporte
     *
     * @param ReporteActividad $reporte
     * @return array
     */
    private function generaHorasReparacion(ReporteActividad $reporte)
    {
        $cantidad = $reporte->actividades()->where('tipo_hora', TipoHora::REPARACION_MAYOR)->sum('cantidad');
        $horas    = ['tipo' => TipoHora::REPARACION_MAYOR, 'cantidad' => $cantidad];
        $this->horasReparacion -= $cantidad;

        return $horas;
    }

    /**
     * Obtiene las horas de ocio de un reporte
     *
     * @param ReporteActividad $reporte
     * @return array
     */
    private function generaHorasOcio(ReporteActividad $reporte)
    {
        $cantidad = $this->ocioPorDia + $reporte->actividades()->where('tipo_hora', TipoHora::OCIO)->sum('cantidad');

        if ($this->horasOcio < $cantidad) {
            $cantidad = $this->horasOcio;
        }

        $horas = ['tipo' => TipoHora::OCIO, 'cantidad' => $cantidad];
        $this->horasOcio -= $cantidad;

        return $horas;
    }
}
