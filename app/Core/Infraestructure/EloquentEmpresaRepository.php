<?php namespace Ghi\Core\Infraestructure;

use Ghi\Core\App\BaseRepository;
use Ghi\Core\Domain\Empresa;
use Ghi\Core\Domain\EmpresaRepository;

class EloquentEmpresaRepository extends BaseRepository implements EmpresaRepository {

    /**
     * Obtiene una empresa por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return Empresa::findOrFail($id);
    }

    /**
     * Obtiene todos los proveedores
     *
     * @return mixed
     */
    public function getAll()
    {
        return Empresa::orderBy('razon_social')->get();
    }

    /**
     * Obtiene los proveedores que tienen entradas de equipo
     *
     * @return mixed
     */
    public function getWithEntradasEquipo()
    {
        return Empresa::has('entradasEquipo')
            ->orderBy('razon_social')
            ->get();
    }

}
