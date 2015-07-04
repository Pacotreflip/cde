<?php

namespace Ghi\Domain\Core\Conceptos;

use League\Fractal\TransformerAbstract;

class ConceptoJsTreeTransformer extends TransformerAbstract
{
    /**
     * @param Concepto $concepto
     * @return array
     */
    public function transform(Concepto $concepto)
    {
        return [
            'id' => $concepto->id_concepto,
            'nivel' => $concepto->nivel,
            'text' => $concepto->clave_concepto ? $concepto->clave_concepto.' - '.$concepto->descripcion : $concepto->descripcion,
            'children' => $concepto->tieneDescendientes(),
            'type' => $concepto->esMaterial() ? 'material' : ($concepto->esMedible() ? 'medible' : 'concepto'),
        ];
    }
}
