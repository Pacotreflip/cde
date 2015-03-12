<?php namespace Ghi\Core\Infraestructure\Almacenes;

use Ghi\Core\App\BaseRepository;
use Ghi\Core\Domain\Almacenes\Material;

class EloquentMaterialRepository extends BaseRepository {

    /**
     * Obtiene los materiales de tipo maquinaria como una lista (id, descripcion)
     * @return mixed
     */
    public function getAllTipoMaquinariaAsList()
    {
        return Material::whereTipoMaterial(8)
            ->whereEquivalencia(0)
            ->whereMarca(1)
            ->orderBy('descripcion')
            ->lists('descripcion', 'id_material');
    }
} 