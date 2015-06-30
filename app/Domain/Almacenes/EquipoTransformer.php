<?php

namespace Ghi\Domain\Almacenes;

use League\Fractal\TransformerAbstract;

class EquipoTransformer extends TransformerAbstract
{
    /**
     * Transformador de equipo
     *
     * @param Inventario $item
     * @return array
     */
    public function transform(Inventario $item)
    {
        return [
            'id_item' => (int) $item->id_item,
            'numero_serie' => $item->referencia,
        ];
    }
}
