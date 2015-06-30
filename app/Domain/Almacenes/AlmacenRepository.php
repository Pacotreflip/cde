<?php

namespace Ghi\Domain\Almacenes;

interface AlmacenRepository
{
    /**
     * Obtiene un almacen por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Obtiene todos los almacenes de una obra
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Obtiene todos los almacenes de una obra paginados
     *
     * @param int $howMany
     * @return mixed
     */
    public function getAllPaginated($howMany = 30);

    /**
     * Obtiene almacenes de un tipo
     *
     * @param $tipo
     * @return mixed
     */
    public function getByTipoAlmacen($tipo);

    /**
     * Obtiene los almacenes de tipo maquinaria control de insumos
     *
     * @return mixed
     */
    public function getAllTipoMaquinariaControl();
}
