<?php namespace Ghi\Core\Domain;

interface EmpresaRepository {

    /**
     * Obtiene un proveedor por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Obtiene todos los proveedores
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Obtiene los proveedores que tienen entradas de equipo
     *
     * @return mixed
     */
    public function getWithEntradasEquipo();

}
