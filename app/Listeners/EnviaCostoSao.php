<?php

namespace Ghi\Listeners;

use Ghi\Domain\Conciliacion\Contracts\CalculadoraPartesUso;
use Ghi\Events\ConciliacionFueAprobada;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviaCostoSao
{
    /**
     * @var CalculadoraPartesUso
     */
    private $calculadoraPartesUso;

    /**
     * Create the event listener.
     *
     * @param CalculadoraPartesUso $calculadoraPartesUso
     */
    public function __construct(CalculadoraPartesUso $calculadoraPartesUso)
    {
        $this->calculadoraPartesUso = $calculadoraPartesUso;
    }

    /**
     * Handle the event.
     *
     * @param  ConciliacionFueAprobada  $event
     * @return void
     */
    public function handle(ConciliacionFueAprobada $event)
    {
        // Calcula las partes de uso que se deben aplicar como costo en cadeco
        $partesUso = $this->calculadoraPartesUso->calcula($event->conciliacion);

        $event->conciliacion->costo_aplicado = true;
        $event->conciliacion->save();
//dd($partesUso);
        // Aplica las partes de uso en cadeco
        // Convertir a horas de cadeco
        // Aplicar precio de acuerdo al contrato de renta
        // Aplicar al equipo activo en el periodo de la conciliacion
        // Validar que la parte de uso en la fecha no exista en cadeco

//        $id_obra          = $conciliacion->almacen->obra->id_obra;
//        $id_empresa       = $conciliacion->id_empresa;
//        $id_almacen       = $this->conciliacion->id_almacen;
//        $fecha_inicial    = $conciliacion->fecha_inicial;
//        $fecha_final      = $conciliacion->fecha_final;
//        $usuario          = auth()->user()->usuario;
//        $equipo_activo    = $this->almacenRepository->getEquipoActivo($id_empresa, $id_almacen, $fecha_inicial, $fecha_final);
//        $contrato         = $this->almacenRepository->getContratoVigente($id_empresa, $id_almacen, $fecha_inicial, $fecha_final);
//          $concepto = Concepto::find($id_concepto);
//                $items[] = new ItemParteUso([
//                    'id_almacen'      => $id_almacen,
//                    'id_concepto'     => $id_concepto,
//                    'unidad'          => $concepto->unidad,
//                    'numero'          => 0,
//                    'cantidad'        => $cantidad,
//                    'precio_unitario' => $contrato->precio_unitario,
//                    'importe'         => $cantidad * $contrato->precio_unitario,
//                    'anticipo'        => $contrato->anticipo,
//                    'id_material'     => $equipo_activo->id_material,
//                    'referencia'      => $equipo_activo->referencia,
//                ]);
//            $parte_uso = ParteUso::crear($id_obra, $reporte->fecha, $id_almacen, $usuario);
//            $parte_uso->observaciones = $reporte->observaciones;
//            $parte_uso->save();
//            $parte_uso->items()->saveMany($items);
    }
}
