<?php

namespace Ghi\Equipamiento\Areas;

class TipoAreaRepository
{
    /**
     * Obtiene un tipo de area por su id
     *
     * @param int $id
     * @return Tipo
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return Tipo::findOrFail($id);
    }

    /**
     * Obtiene la estructura completa de tipos
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return Tipo::defaultOrder()->withDepth()->get();
    }

    /**
     * Obtiene los tipos que son raiz
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getNivelesRaiz()
    {
        return Tipo::whereIsRoot()->defaultOrder()->get();
    }

    /**
     * Obtiene una lista de tipos como un arreglo
     *
     * @return array
     */
    public function getListaTipos()
    {
        $tipos = $this->getAll();

        $lista = [];
        foreach ($tipos as $tipo) {
            $lista[$tipo->id] = str_repeat('-', $tipo->depth + 1).''.$tipo->nombre;
        }

        return $lista;
    }

    /**
     * Elimina un tipo
     *
     * @param Tipo $tipo
     * @return bool
     */
    public function delete(Tipo $tipo)
    {
        return $tipo->delete();
    }

    /**
     * Persiste los cambios de un tipo
     *
     * @param Tipo $tipo
     * @return bool|mixed
     */
    public function save(Tipo $tipo)
    {
        return $tipo->save();
    }
}