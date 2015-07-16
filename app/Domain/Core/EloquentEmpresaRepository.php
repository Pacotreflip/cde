<?php

namespace Ghi\Domain\Core;

use Illuminate\Database\Eloquent\Collection;

class EloquentEmpresaRepository extends BaseRepository implements EmpresaRepository
{
    /**
     * Obtiene una empresa por su id
     *
     * @param $id
     * @return Empresa
     */
    public function getById($id)
    {
        return Empresa::findOrFail($id);
    }

    /**
     * Obtiene todos los proveedores
     *
     * @return Collection|Empresa
     */
    public function getAll()
    {
        return Empresa::orderBy('razon_social')->get();
    }

    /**
     * Obtiene los proveedores que tienen entradas de equipo
     *
     * @return Collection|Empresa
     */
    public function getProveedoresMaquinaria()
    {
        return Empresa::has('entradasEquipo')
            ->orderBy('razon_social')
            ->get();
    }
}
