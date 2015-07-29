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

class EnviaCostoSao
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
        $usuario       = auth()->user()->usuario;
        $equipo        = $this->almacenRepository->getEquipoActivo($id_empresa, $id_almacen, $fecha_inicial, $fecha_final);
        $contrato      = $this->almacenRepository->getContratoVigente($id_empresa, $id_almacen, $fecha_inicial, $fecha_final);

        // Validar que la parte de uso en la fecha no exista en cadeco
        if ($this->existenPartesUsoEnCadeco($id_almacen, $fecha_inicial, $fecha_final)) {
            throw new ReglaNegocioException('Ya existen partes de uso registradas en cadeco para este periodo.');
        }

        try {
            DB::connection('cadeco')->beginTransaction();

            foreach ($partes as $parte) {
                $items = [];
                $parte_uso = ParteUso::crear($id_obra, $parte['fecha'], $id_almacen, $usuario);
                $parte_uso->save();

                // Convertir a horas de cadeco
                // Aplicar precio de acuerdo al contrato de renta
                // Aplicar al equipo activo en el periodo de la conciliacion
                foreach ($parte['horas'] as $hora) {
                    $item = new ItemParteUso([
                        'id_almacen'      => $id_almacen,
                        'cantidad'        => $hora['cantidad'],
                        'numero'          => $this->getTipoHoraCadeco($hora['tipo']),
                        'precio_unitario' => $contrato->precio_unitario,
                        'importe'         => $hora['cantidad'] * $contrato->precio_unitario,
                        'anticipo'        => $contrato->anticipo,
                        'id_material'     => $equipo->id_material,
                        'referencia'      => $equipo->referencia,
                    ]);

                    if ($hora['tipo'] == TipoHora::EFECTIVA) {
                        $concepto = Concepto::findOrFail($hora['id_concepto']);
                        $item->concepto()->associate($concepto);
                        $item->unidad = $concepto->unidad;
                    }

                    $items[] = $item;
                }

                $parte_uso->items()->saveMany($items);
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
}
