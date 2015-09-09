<?php

namespace Ghi\Equipamiento\Articulos;

class ClasificadorRepository
{
    /**
     * Obtiene un clasificador por su id
     *
     * @param int $id
     * @return Clasificador
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return Clasificador::findOrFail($id);
    }

    /**
     * Obtiene la estructura completa de clasificadores
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return Clasificador::defaultOrder()->withDepth()->get();
    }

    /**
     * Obtiene los clasificadores que son raiz
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNivelesRaiz()
    {
        return Clasificador::whereIsRoot()->defaultOrder()->get();
    }

    /**
     * Obtiene una lista de clasificadores como un arreglo
     *
     * @return array
     */
    public function getAsList()
    {
        $clasificadores = $this->getAll();

        $lista = [];
        foreach ($clasificadores as $clasificador) {
            $lista[$clasificador->id] = str_repeat('-', $clasificador->depth + 1).''.$clasificador->nombre;
        }

        return $lista;
    }

    /**
     * Elimina un clasificador
     *
     * @param Clasificador $clasificador
     * @return bool
     */
    public function delete(Clasificador $clasificador)
    {
        return $clasificador->delete();
    }

    /**
     * Persiste los cambios de un clasificador
     *
     * @param Clasificador $clasificador
     * @return bool|mixed
     */
    public function save(Clasificador $clasificador)
    {
        return $clasificador->save();
    }
}