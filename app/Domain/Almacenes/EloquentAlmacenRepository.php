<?php

namespace Ghi\Domain\Almacenes;

use Ghi\Domain\Core\BaseRepository;
use Ghi\Almacenes\Domain\Almacen;
use Ghi\Almacenes\Domain\AlmacenRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentAlmacenRepository extends BaseRepository implements AlmacenRepository
{
    /**
     * Obtiene todos los almacenes de una obra
     *
     * @return Collection|Almacen
     */
    public function getAll()
    {
        return Almacen::whereIdObra($this->context->getId())
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * Obtiene todos los almacenes de una obra paginados
     *
     * @param int $howMany
     * @return Collection|Almacen
     */
    public function getAllPaginated($howMany = 30)
    {
        return Almacen::where('id_obra', $this->context->getId())
            ->orderBy('descripcion', 'asc')
            ->paginate($howMany);
    }

    /**
     * Obtiene un almacen por su id
     *
     * @param $id
     * @return Almacen
     */
    public function getById($id)
    {
        return Almacen::where('id_almacen', $id)
            ->firstOrFail();
    }

    /**
     * Obtiene almacenes de un tipo
     *
     * @param $tipo
     * @return Collection|Almacen
     */
    public function getByTipoAlmacen($tipo)
    {
        return Almacen::where('id_obra', $this->context->getId())
            ->where('tipo_almacen', $tipo)
            ->get();
    }

    /**
     * Obtiene los almacenes de tipo maquinaria control de insumos
     *
     * @return Collection|Almacen
     */
    public function getAllTipoMaquinariaControl()
    {
        return $this->getByTipoAlmacen(Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS);
    }
}
