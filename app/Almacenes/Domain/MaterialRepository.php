<?php namespace Ghi\Almacenes\Domain;

interface MaterialRepository
{
    /**
     * Obtiene un material por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Obtiene los materiales de tipo maquinaria en forma de lista
     *
     * @return mixed
     */
    public function getByTipoMaquinariaList();

}
