<?php

namespace Ghi\Domain\Almacenes;

use Illuminate\Support\Facades\URL;
use League\Fractal\TransformerAbstract;

class AlmacenTransformer extends TransformerAbstract
{
    /**
     * Transformador de almacen
     *
     * @param AlmacenMaquinaria $almacen
     * @return array
     */
    public function transform(AlmacenMaquinaria $almacen)
    {
        return [
            'id' => (int) $almacen->id_almacen,
            'descripcion' => $almacen->descripcion,
            'numero_economico' => $almacen->numero_economico,
            'url' => URL::to('api/almacenes/' . $almacen->id_almacen),
            'categoria' => $almacen->categoria->descripcion,
            'propiedad' => $almacen->propiedad->descripcion,
        ];
    }

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'equipos',
    ];

    /**
     * Include Equipos
     *
     * @param AlmacenMaquinaria $almacen
     * @return \League\Fractal\Resource\Collection
     */
    public function includeEquipos(AlmacenMaquinaria $almacen)
    {
        return $this->collection($almacen->equipos, new EquipoTransformer);
    }
}
