<?php

namespace Ghi\Domain\Core;

class EloquentMaterialRepository extends BaseRepository implements MaterialRepository
{
    /**
     * Obtiene un material por su id
     *
     * @param $id
     * @return Material
     */
    public function getById($id)
    {
        return Material::findOrFail($id);
    }

    /**
     * Obtiene los materiales de tipo maquinaria como una lista (id, descripcion)
     *
     * @return array
     */
    public function getByTipoMaquinariaList()
    {
        return Material::selectRaw("id_material, descripcion + ' - [' + numero_parte + ']'AS descripcion_codigo, descripcion")
            ->where('tipo_material', Material::TIPO_MAQUINARIA)
            ->where('marca', 1)
            ->orderBy('descripcion')
            ->lists('descripcion_codigo', 'id_material')
            ->all();
    }
}
