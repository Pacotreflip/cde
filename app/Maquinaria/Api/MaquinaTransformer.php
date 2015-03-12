<?php namespace Ghi\Maquinaria\Api;

use Ghi\Maquinaria\Domain\Conciliacion\Models\ItemEntradaEquipo;

class MaquinaTransformer {

    /**
     * Transformador de maquina
     * @param ItemEntradaEquipo $item
     * @return array
     */
    public function transform(ItemEntradaEquipo $item)
    {
        return [
            'id_item' => (int) $item->id_item,
            'numero_serie' => $item->referencia,
        ];
    }
}