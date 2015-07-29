<?php

namespace Ghi\Domain\Conciliacion;

class ActividadAParteUsoConverter
{

    /**
     * @param Actividad $actividad
     * @param ContratoRenta $contrato
     * @param Maquina $maquina
     * @return array
     */
    public function convert(Actividad $actividad, ContratoRenta $contrato, Maquina $maquina)
    {
        $item = new ItemParteUso([
            'id_almacen'  => $actividad->id_almacen,
            'id_concepto' => $actividad->id_concepto,
            'unidad'      => $actividad->tieneDestino() ? $actividad->concepto->unidad : null,
            'numero'      => $this->convierteTipoHora($actividad->id_tipo_hora),
            'cantidad'    => $actividad->cantidad,
            'precio_unitario' => $contrato->precio_unitario,
            'importe' => $actividad->cantidad * $contrato->precio_unitario,
            'anticipo' => $contrato->anticipo,
            'id_material' => $maquina->id_material,
            'referencia' => $maquina->referencia,
        ]);

        return $item;
    }

    /**
     * @param $tipoHora
     * @return mixed
     */
    protected function convierteTipoHora($tipoHora)
    {
        if ($tipoHora == TipoHora::EFECTIVA) {
            return ItemParteUso::TIPO_HORA_TRABAJADA;
        }

        if ($tipoHora == TipoHora::OCIO || $tipoHora == TipoHora::REPARACION_MENOR || $tipoHora == TipoHora::MANTENIMIENTO) {
            return ItemParteUso::TIPO_HORA_ESPERA;
        }

        if ($tipoHora == TipoHora::REPARACION_MAYOR) {
            return ItemParteUso::TIPO_HORA_REPARACION;
        }

        throw new \InvalidArgumentException('El tipo de hora es invalido');
    }
}
