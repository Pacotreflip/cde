<?php

namespace Ghi\Domain\Core;

interface MaterialRepository
{
    /**
     * Obtiene un material por su id
     *
     * @param $id
     * @return Material
     */
    public function getById($id);

    /**
     * Obtiene los materiales de tipo maquinaria en forma de lista
     *
     * @return array|Material
     */
    public function getByTipoMaquinariaList();
}
