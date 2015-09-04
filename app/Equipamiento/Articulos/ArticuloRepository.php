<?php

namespace Ghi\Equipamiento\Articulos;

class ArticuloRepository
{
    /**
     * Busca un articulo por su id
     *
     * @return Articulo
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return Articulo::findOrFail($id);
    }

    /**
     * Obtiene todos los articulos paginados
     *
     * @param int $howMany
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPaginated($howMany = 30)
    {
        return Articulo::orderBy('nombre')->paginate($howMany);
    }

    /**
     * Busqueda de articulos
     *
     * @param string $busqueda Texto a buscar
     * @param int $howMany Numero de articulos por pagina
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function buscar($busqueda, $howMany = 30)
    {
        return Articulo::where('nombre', 'LIKE', '%'.$busqueda.'%')
            ->orWhere('numero_parte', 'LIKE', '%'.$busqueda.'%')
            ->orWhere('descripcion', 'LIKE', '%'.$busqueda.'%')
            ->paginate($howMany);
    }

    /**
     * Obtiene una lista de unidades como arreglo
     *
     * @return array
     */
    public function getListaUnidades()
    {
        return Unidad::orderBy('codigo')->lists('codigo', 'codigo')->all();
    }

    /**
     * Obtiene una lista de clasificadores como arreglo
     *
     * @return array
     */
    public function getListaClasificadores()
    {
        return Clasificador::orderBy('nombre')->lists('nombre', 'id')->all();
    }

    /**
     * Guarda los cambios de un articulo
     * @param Articulo $articulo
     * @return bool
     */
    public function save(Articulo $articulo)
    {
        return $articulo->save();
    }
}