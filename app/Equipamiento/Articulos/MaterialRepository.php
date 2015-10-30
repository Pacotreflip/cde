<?php

namespace Ghi\Equipamiento\Articulos;

use Ghi\Equipamiento\Articulos\Unidad;
use Ghi\Equipamiento\Articulos\Material;

class MaterialRepository
{
    /**
     * Busca un material por su id
     *
     * @return Material
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return Material::findOrFail($id);
    }

    /**
     * Obtiene todos los materiales
     *
     * @param array $except Ids de los materiales a excluir
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAll($except = [])
    {
        return Material::soloMateriales()
            ->whereNotIn('id_material', $except)
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * Obtiene todos los materiales paginados
     *
     * @param int $howMany
     * @param array $except Ids de los materiales a excluir
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPaginated($howMany = 30, $except = [])
    {
        return Material::soloMateriales()
            ->whereNotIn('id_material', $except)
            ->orderBy('descripcion')
            ->paginate($howMany);
    }

    /**
     * Obtiene los materiales en forma de lista
     * 
     * @return array
     */
    public function getAsList()
    {
        return Material::soloMateriales()
            ->orderBy('descripcion')
            ->lists('descripcion', 'id_material')
            ->all();
    }

    /**
     * Busqueda de materiales
     *
     * @param string $busqueda Texto a buscar
     * @param int $howMany Numero de articulos por pagina
     * @param array $except Ids de los materiales a excluir
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function buscar($busqueda, $howMany = 30, $except = [])
    {
        return Material::soloMateriales()
            ->whereNotIn('id_material', $except)
            ->where(function ($query) use($busqueda) {
                $query->where('descripcion', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('numero_parte', 'LIKE', '%'.$busqueda.'%')
                    ->orWhere('unidad', 'LIKE', '%'.$busqueda.'%');
            })
            ->orderBy('descripcion')
            ->paginate($howMany);
    }

    /**
     * Obtiene una lista de unidades como arreglo
     *
     * @return array
     */
    public function getListaUnidades()
    {
        return Unidad::selectRaw('unidad AS id, unidad')
            ->orderBy('unidad')->lists('unidad', 'unidad')->all();
    }

    /**
     * Obtiene una lista de clasificadores como arreglo
     *
     * @return array
     */
    public function getListaClasificadores()
    {
        return Clasificador::orderBy('nombre')
            ->lists('nombre', 'id')->all();
    }

    /**
     * Obtiene una lista de las familias de materiales
     *
     * @return array
     */
    public function getListaFamilias($tipo = null)
    {
        return Familia::familias($tipo)
            ->orderBy('descripcion')
            ->lists('descripcion', 'id_material');
    }

    /**
     * Obtiene una lista de los tipos de material como arreglo
     *
     * @return array
     */
    public function getListaTipoMateriales()
    {
        return [
            TipoMaterial::TIPO_MATERIALES => (new TipoMaterial(TipoMaterial::TIPO_MATERIALES))->getDescripcion(),
            TipoMaterial::TIPO_MANO_OBRA  => (new TipoMaterial(TipoMaterial::TIPO_MANO_OBRA))->getDescripcion(),
            TipoMaterial::TIPO_SERVICIOS  => (new TipoMaterial(TipoMaterial::TIPO_SERVICIOS))->getDescripcion(),
            TipoMaterial::TIPO_HERRAMIENTA_Y_EQUIPO => (new TipoMaterial(TipoMaterial::TIPO_HERRAMIENTA_Y_EQUIPO))->getDescripcion(),
            TipoMaterial::TIPO_MAQUINARIA => (new TipoMaterial(TipoMaterial::TIPO_MAQUINARIA))->getDescripcion(),
        ];
    }

    /**
     * Guarda los cambios de un material
     * @param Mateiral $material
     * @return bool
     */
    public function save(Material $material)
    {
        return $material->save();
    }
}