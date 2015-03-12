<?php namespace Ghi\Conciliacion\Infraestructure;

use Ghi\Conciliacion\Domain\ProveedorRepository;
use Ghi\Conciliacion\Domain\Proveedor;
use Ghi\Core\App\BaseRepository;

class EloquentProveedorRepository extends BaseRepository implements ProveedorRepository {

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return Proveedor::findOrFail($id);
    }

    /**
     * Obtiene los proveedores que rentan equipo a la empresa
     * de acuerdo a las entradas de equipo generadas de cada
     * proveedor
     * @param $idObra
     * @return mixed
     */
    public function findAll($idObra)
    {
        return Proveedor::whereHas('entradas', function($query) use ($idObra)
            {
                $query->whereIdObra($idObra);
            })
            ->orderBy('razon_social')
            ->get();
    }

}