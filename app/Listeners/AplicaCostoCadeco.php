<?php

namespace Ghi\Listeners;

use DB;
use Ghi\Domain\Almacenes\AlmacenMaquinariaRepository;
use Ghi\Domain\Conciliacion\Contracts\CalculadoraPartesUso;
use Ghi\Domain\Conciliacion\ItemParteUso;
use Ghi\Domain\Conciliacion\ParteUso;
use Ghi\Domain\Core\Conceptos\Concepto;
use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\Domain\ReportesActividad\TipoHora;
use Ghi\Events\ConciliacionFueAprobada;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AplicaCostoCadeco
{
    /**
     * @var CalculadoraPartesUso
     */
    private $calculadoraPartesUso;

    /**
     * @var AlmacenMaquinariaRepository
     */
    private $almacenRepository;

    /**
     * Create the event listener.
     *
     * @param CalculadoraPartesUso $calculadoraPartesUso
     * @param AlmacenMaquinariaRepository $almacenRepository
     */
    public function __construct(CalculadoraPartesUso $calculadoraPartesUso, AlmacenMaquinariaRepository $almacenRepository)
    {
        $this->calculadoraPartesUso = $calculadoraPartesUso;
        $this->almacenRepository    = $almacenRepository;
    }

    /**
     * Handle the event.
     *
     * @param  ConciliacionFueAprobada $event
     * @throws \Exception
     */
    public function handle(ConciliacionFueAprobada $event)
    {
        $conciliacion = $event->conciliacion;

        // Calcula las partes de uso de una conciliacion aprobada
        $partes = $this->calculadoraPartesUso->calcula($conciliacion);

        $id_obra       = $conciliacion->almacen->obra->id_obra;
        $id_empresa    = $conciliacion->id_empresa;
        $id_almacen    = $conciliacion->id_almacen;
        $fecha_inicial = $conciliacion->fecha_inicial;
        $fecha_final   = $conciliacion->fecha_final;
        $equipo        = $this->almacenRepository->getEquipoActivo($id_empresa, $id_almacen, $fecha_inicial, $fecha_final);

        if ($this->existenPartesUsoEnCadeco($id_almacen, $fecha_inicial, $fecha_final)) {
            throw new ReglaNegocioException('Ya existen partes de uso registradas en cadeco para este periodo.');
        }

        try {
            DB::connection('cadeco')->beginTransaction();

            foreach ($partes as $parte) {
                $parte_uso = $this->creaParteUso($id_obra, $id_empresa, $id_almacen, $parte['fecha'], $parte['horas']);
            }

            DB::connection('cadeco')->commit();
        } catch (\Exception $e) {
            DB::connection('cadeco')->rollback();
            throw $e;
        }

        $conciliacion->costo_aplicado = true;
        $conciliacion->save();
    }

    /**
     * Obtiene el tipo de hora que se debe aplicar para cadeco
     *
     * @param $tipo
     * @return int
     */
    private function getTipoHoraCadeco($tipo)
    {
        if ($tipo == TipoHora::EFECTIVA) {
            return ItemParteUso::TRABAJADA;
        }

        if ($tipo == TipoHora::OCIO) {
            return ItemParteUso::ESPERA;
        }

        if ($tipo == TipoHora::REPARACION_MAYOR) {
            return ItemParteUso::REPARACION;
        }

        throw new \InvalidArgumentException('Tipo de hora no válida');
    }

    /**
     * Identifica si ya existen partes de uso registradas en un periodo para un almacen en cadeco
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return mixed
     */
    private function existenPartesUsoEnCadeco($id_almacen, $fecha_inicial, $fecha_final)
    {
        return ParteUso::where('tipo_transaccion', ParteUso::TIPO_TRANSACCION)
            ->where('id_almacen', $id_almacen)
            ->whereBetween('fecha', [$fecha_inicial, $fecha_final])
            ->exists();
    }

    /**
     * Crea una nueva parte de uso en cadeco
     *
     * @param $id_obra
     * @param $id_empresa
     * @param $id_almacen
     * @param $fecha
     * @param array $horas
     * @return ParteUso
     */
    private function creaParteUso($id_obra, $id_empresa, $id_almacen, $fecha, array $horas = [])
    {
        $equipo    = $this->almacenRepository->getEquipoActivo($id_empresa, $id_almacen, $fecha, $fecha);
        $contrato  = $this->almacenRepository->getContratoVigente($id_empresa, $id_almacen, $fecha, $fecha);
        $items     = [];

        // Convertir a horas de cadeco
        // Aplicar precio de acuerdo al contrato de renta
        // Aplicar al equipo activo en el periodo de la conciliacion
        foreach ($horas as $hora) {
            $item                  = new ItemParteUso;
            $item->id_almacen      = $id_almacen;
            $item->cantidad        = $hora['cantidad'];
            $item->numero          = $this->getTipoHoraCadeco($hora['tipo']);
            $item->id_material     = $equipo->id_material;
            $item->referencia      = $equipo->referencia;
            $item->precio_unitario = $contrato->precio_unitario;
            $item->anticipo        = $contrato->anticipo;

            if ($item->aplicaParaCosto()) {
                $item->importe       = $hora['cantidad'] * $contrato->precio_unitario;
                $equipo->monto_total = $equipo->monto_total + $item->importe;
                $equipo->cantidad    = $equipo->cantidad + $item->cantidad;
            }

            if ($item->numero == ItemParteUso::ESPERA) {
                $equipo->saldo = $equipo->saldo + $item->cantidad;
            }

            if ($item->numero == ItemParteUso::TRABAJADA) {
                $concepto = Concepto::findOrFail($hora['id_concepto']);
                $item->concepto()->associate($concepto);
                $item->unidad = $concepto->unidad;
            }

            $items[] = $item;
        }

        $parte_uso = ParteUso::crear($id_obra, $fecha, $id_almacen, auth()->user()->usuario);
        $parte_uso->save();
        $parte_uso->items()->saveMany($items);
        $equipo->save();

        return $parte_uso;
    }
}
