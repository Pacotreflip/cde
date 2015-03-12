<?php namespace Ghi\Conciliacion\Domain\Rentas;

use Ghi\Conciliacion\Domain\Rentas\ContratoRenta;
use Ghi\Conciliacion\Domain\Rentas\ItemParteUso;
use Ghi\Operacion\Domain\Hora;
use Ghi\Operacion\Domain\HoraTipo;
use Ghi\SharedKernel\Models\Maquina;

class HoraAParteUsoConverter {

    /**
     * @param Hora $hora
     * @param ContratoRenta $contrato
     * @param Maquina $maquina
     * @return array
     */
    public function convert(Hora $hora, ContratoRenta $contrato, Maquina $maquina)
    {
        $item = new ItemParteUso([
            'id_almacen'  => $hora->id_almacen,
            'id_concepto' => $hora->id_concepto,
            'unidad'      => $hora->tieneDestino() ? $hora->concepto->unidad : null,
            'numero'      => $this->convierteTipoHora($hora->id_tipo_hora),
            'cantidad'    => $hora->cantidad,
            'precio_unitario' => $contrato->precio_unitario,
            'importe' => $hora->cantidad * $contrato->precio_unitario,
            'anticipo' => $contrato->anticipo,
            'id_material' => $maquina->id_material,
            'referencia' => $maquina->referencia,
        ]);

        return $item;
    }

    /**
     * @param $tipoHora
     * @return int
     */
    protected function convierteTipoHora($tipoHora)
    {
        if ($tipoHora == HoraTipo::EFECTIVA)
        {
            return ItemParteUso::TIPO_HORA_TRABAJADA;
        }

        if ($tipoHora == HoraTipo::OCIO || $tipoHora == HoraTipo::REPARACION_MENOR || $tipoHora == HoraTipo::MANTENIMIENTO)
        {
            return ItemParteUso::TIPO_HORA_ESPERA;
        }

        if ($tipoHora == HoraTipo::REPARACION_MAYOR)
        {
            return ItemParteUso::TIPO_HORA_REPARACION;
        }

        throw new \InvalidArgumentException('El tipo de hora es invalido');
    }
}