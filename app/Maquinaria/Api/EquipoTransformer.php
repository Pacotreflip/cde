<?php namespace Ghi\Maquinaria\Api;

use Ghi\SharedKernel\Models\Equipo;
use League\Fractal\TransformerAbstract;

class EquipoTransformer extends TransformerAbstract {

    /**
     * Transformador de equipo
     * @param Equipo $equipo
     * @return array
     */
    public function transform(Equipo $equipo)
    {
        return [
            'id' => (int) $equipo->id_almacen,
            'descripcion' => $equipo->descripcion,
            'numero_economico' => $equipo->numero_economico,
            'url' => \URL::to('api/equipos/' . $equipo->id_almacen),
//            'categoria' => $equipo->categoria,
//            'propiedad' => $equipo->propiedad,
        ];
    }

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'maquinas',
    ];

    /**
     * Include Maquinas
     *
     * @param Equipo $equipo
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMaquinas(Equipo $equipo)
    {
        $maquinas = $equipo->maquinas;

        return $this->collection($maquinas, new MaquinaTransformer);
    }
}