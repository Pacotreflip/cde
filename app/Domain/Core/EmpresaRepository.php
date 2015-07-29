<?php

namespace Ghi\Domain\Core;

use Illuminate\Database\Eloquent\Collection;

interface EmpresaRepository
{
    /**
     * Obtiene un proveedor por su id
     *
     * @param $id
     * @return Empresa
     */
    public function getById($id);

    /**
     * Obtiene todos los proveedores
     *
     * @return Collection|Empresa
     */
    public function getAll();

    /**
     * Obtiene los proveedores que tienen entradas de equipo
     *
     * @return Collection|Empresa
     */
    public function getProveedoresMaquinaria();
}
