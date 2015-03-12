<?php  namespace Ghi\Core\Infraestructure\Almacenes; 

use Ghi\Core\App\BaseRepository;
use Ghi\Core\Domain\Almacenes\Almacen;
use Ghi\Core\Domain\Almacenes\AlmacenMaquinaria;
use Ghi\Core\Domain\Almacenes\AlmacenMaquinariaRepository;

class EloquentAlmacenMaquinariaRepository extends BaseRepository implements AlmacenMaquinariaRepository{

    /**
     * Obtiene un almacen por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return AlmacenMaquinaria::findOrFail($id);
    }

    /**
     * Obtiene todos los almacenes de una obra
     *
     * @return mixed
     */
    public function getAll()
    {
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->where('tipo_almacen', Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS)
            ->orderBy('descripcion', 'asc')
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
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->where('tipo_almacen', Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS)
            ->orderBy('descripcion', 'asc')
            ->paginate($howMany);
    }

    /**
     * Busca los almacenes de maquinaria de un proveedor
     * a traves de las entradas de equipo en almacen
     *
     * @param $idObra
     * @param $idEmpresa
     * @return mixed
     */
    public function findByIdProveedor($idObra, $idEmpresa)
    {
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->where('tipo_almacen', Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS)
            ->whereHas('maquinas', function($query) use($idObra, $idEmpresa)
            {
                $query->whereHas('entrada', function($query) use($idEmpresa)
                {
                    $query->whereIdEmpresa($idEmpresa);
                });
            })
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * Obtiene la maquina que entro en un almacen y que esta activa
     * en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return mixed
     * @throws ReglaNegocioException
     */
    public function findMaquinaActivaEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        $maquina =  AlmacenMaquinaria::where('id_almacen', $idAlmacen)
            ->where('tipo_almacen', Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS)
            ->where(function($query) use($fechaInicial, $fechaFinal)
            {
                $query->where('fecha_desde', '<=', $fechaInicial)
                    ->where(function($query) use($fechaFinal)
                    {
                        $query->where('fecha_hasta', '>=', $fechaFinal)
                            ->orWhereNull('fecha_hasta');
                    });
            })
            ->first();

        if ( ! $maquina)
        {
            throw new ReglaNegocioException('No existe una maquina activa en el periodo indicado para este almacen.');
        }

        return $maquina;
    }

    /**
     * Obtiene una lista de categorias
     *
     * @return mixed
     */
    public function getCategoriasList()
    {
        return Categoria::all()
            ->sortBy('descripcion')
            ->lists('descripcion', 'id_categoria');
    }

    /**
     * Obtiene una lista de propiedades
     *
     * @return mixed
     */
    public function getPropiedadesList()
    {
        return Propiedad::all()
            ->lists('descripcion', 'id_propiedad');
    }
}