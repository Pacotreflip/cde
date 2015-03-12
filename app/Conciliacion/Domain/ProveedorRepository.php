<?php namespace Ghi\Conciliacion\Domain\Rentas;

interface ProveedorRepository {

    /**
     * Obtiene un proveedor por su id
     *
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Obtiene los proveedores que rentan equipo a la empresa
     * de acuerdo a las entradas de equipo que existan
     *
     * @param $idObra
     * @return mixed
     */
    public function findAll($idObra);

}