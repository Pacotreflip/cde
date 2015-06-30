<?php

namespace Ghi\Domain\Core\Conceptos;

use League\Fractal\TransformerAbstract;

class ConceptoTransformer extends TransformerAbstract
{
    /**
     * @param Concepto $concepto
     * @return array
     */
    public function transform(Concepto $concepto)
    {
        return [
            'id' => (int) $concepto->id_concepto,
            'clave' => $concepto->clave_concepto,
            'descripcion' => $concepto->descripcion,
            'nivel' => $concepto->nivel,
            'medible' => (bool) $concepto->esMedible(),
            'url' => \URL::to('api/conceptos/' . $concepto->id_concepto),
        ];
    }
}
