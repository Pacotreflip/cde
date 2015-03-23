<?php namespace Ghi\Almacenes\Infraestructure;

use Ghi\Core\App\BaseRepository;
use Ghi\Almacenes\Domain\Almacen;
use Ghi\Almacenes\Domain\AlmacenRepository;

class EloquentAlmacenRepository extends BaseRepository implements AlmacenRepository
{
    /**
     * Obtiene todos los almacenes de una obra
     *
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
     */
    public function getAllTipoMaquinariaControl()
    {
        return $this->getByTipoAlmacen(Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS);
    }

}
