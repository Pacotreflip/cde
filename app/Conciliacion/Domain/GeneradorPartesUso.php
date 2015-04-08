<?php namespace Ghi\Conciliacion\Domain\Rentas;

use Ghi\Conciliacion\Domain\Rentas\ContratoRentaRepository;
use Ghi\Conciliacion\Domain\Rentas\ItemParteUso;
use Ghi\Conciliacion\Domain\Rentas\ParteUso;
use Ghi\Conciliacion\Domain\Events\PeriodoFueCerrado;
use Ghi\Conciliacion\Domain\Periodos\ConciliacionRepository;
use Ghi\Operacion\Domain\ReporteActividadRepository;
use Ghi\SharedKernel\Contracts\EquipoRepository;
use Laracasts\Commander\Events\EventListener;

class GeneradorPartesUso extends EventListener {

    /**
     * @var
     */
    protected $trabajadas;
    /**
     * @var
     */
    protected $espera;
    /**
     * @var
     */
    protected $reparacion;

    /**
     * @var ReporteActividadRepository
     */
    private $operacionRepository;

    /**
     * @var ConciliacionRepository
     */
    private $periodoRepository;

    /**
     * @var HoraAParteUsoConverter
     */
    private $converter;

    /**
     * @var ContratoRentaRepository
     */
    private $contratoRentaRepository;

    /**
     * @var EquipoRepository
     */
    private $equipoRepository;

    /**
     * @param ConciliacionRepository $periodoRepository
     * @param ReporteActividadRepository $operacionRepository
     * @param HoraAParteUsoConverter $converter
     * @param ContratoRentaRepository $contratoRentaRepository
     * @param EquipoRepository $equipoRepository
     */
    function __construct(
        ConciliacionRepository $periodoRepository,
        ReporteActividadRepository $operacionRepository,
        HoraAParteUsoConverter $converter,
        ContratoRentaRepository $contratoRentaRepository,
        EquipoRepository $equipoRepository
    )
    {
        $this->operacionRepository = $operacionRepository;
        $this->periodoRepository = $periodoRepository;
        $this->converter = $converter;
        $this->contratoRentaRepository = $contratoRentaRepository;
        $this->equipoRepository = $equipoRepository;
    }

    /**
     * @param PeriodoFueCerrado $event
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function whenPeriodoFueCerrado(PeriodoFueCerrado $event)
    {
        // obtener el periodo de conciliacion
        $periodo = $this->periodoRepository->findById($event->id);

        // Sin la operacion completa no se generan partes de uso
        if ( ! $periodo->operacionEstaCompleta())
        {
            return false;
        }

        $contrato = $this->contratoRentaRepository->getContratoVigenteDeEquipoPorPeriodo(
            $periodo->id_obra,
            $periodo->id_empresa,
            $periodo->id_almacen,
            $periodo->fecha_inicial,
            $periodo->fecha_final
        );

        $maquina = $this->equipoRepository->findMaquinaActivaEnPeriodo(
            $periodo->id_almacen, $periodo->fecha_inicial, $periodo->fecha_final
        );

        // obtener los reportes del periodo de conciliacion
        $reportes = $this->operacionRepository->getByPeriodo(
            $periodo->id_almacen, $periodo->fecha_inicial, $periodo->fecha_final
        );

//        $horasContrato = $periodo->horas_contrato;
        $this->trabajadas = $periodo->horas_conciliadas_efectivas;
        $this->espera = $periodo->horas_conciliadas_ocio;
        $this->reparacion = $periodo->horas_conciliadas_reparacion_mayor;

        \DB::beginTransaction();

        try
        {
            foreach ($reportes as $reporte)
            {
                $parteUso = ParteUso::crear(
                    $reporte->id_obra,
                    $reporte->fecha,
                    $reporte->id_almacen,
                    $periodo->usuario
                );

                $parteUso->save();

                $items = [];

                foreach ($reporte->horas as $hora)
                {
                    $item = $this->converter->convert($hora, $contrato, $maquina);

                    if ($item->numero == ItemParteUso::TIPO_HORA_TRABAJADA)
                    {
                        if (($this->trabajadas - $item->cantidad) < 0)
                        {
                            continue;
                        }

                        $this->trabajadas -= $item->cantidad;
                    }

                    if ($item->numero == ItemParteUso::TIPO_HORA_ESPERA)
                    {
                        if (($this->espera - $item->cantidad) < 0)
                        {
                            continue;
                        }

                        $this->espera -= $item->cantidad;
                    }

                    if ($item->numero == ItemParteUso::TIPO_HORA_REPARACION)
                    {
                        if (($this->reparacion - $item->cantidad) < 0)
                        {
                            continue;
                        }

                        $this->reparacion -= $item->cantidad;
                    }

                    $items[] = $item;
                }

                $parteUso->items()->saveMany($items);

                $reporte->conciliado = true;

                $reporte->save();
            }

            \DB::commit();
        }
        catch (Exception $e)
        {
            DB::rollback();

            throw $e;
        }
    }

}