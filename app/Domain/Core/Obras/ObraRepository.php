<?php

namespace Ghi\Domain\Core\Obras;

interface ObraRepository
{
    /**
     * Obtiene una obra por su id
     *
     * @param $id
     * @return Obra
     */
    public function getById($id);
}
