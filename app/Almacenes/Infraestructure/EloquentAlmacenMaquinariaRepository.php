<?php namespace Ghi\Almacenes\Infraestructure;

use Ghi\Almacenes\Domain\Categoria;
use Ghi\Almacenes\Domain\HoraMensual;
use Ghi\Almacenes\Domain\Propiedad;
use Ghi\Core\App\BaseRepository;
use Ghi\Almacenes\Domain\Almacen;
use Ghi\Almacenes\Domain\AlmacenMaquinaria;
use Ghi\Almacenes\Domain\AlmacenMaquinariaRepository;

class EloquentAlmacenMaquinariaRepository extends BaseRepository implements AlmacenMaquinariaRepository{

    /**
     * Obtiene un almacen por su id, incluyendo los
     * equipos que han entrado
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return AlmacenMaquinaria::with(['equipos' => function($query)
        {
            $query->orderBy('fecha_desde', 'ASC');
        }])->whereIn('tipo_almacen', [
            Almacen::TIPO_MAQUINARIA,
            Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS
        ])
            ->where('id_almacen', $id)
            ->firstOrFail();
    }

    /**
     * Obtiene todos los almacenes de una obra
     *
     * @return mixed
     */
    public function getAll()
    {
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA,Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->orderBy('descripcion', 'asc')
            ->get();
    }

    /**
     * Obtiene todos los almacenes de una obra paginados
     *
     * @param int $howMany
     * @return mixed
     */
    public function getAllPaginated($howMany = 50)
    {
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA,Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
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
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA,Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
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
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA,Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
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
     * Obtiene una categoria por su id
     *
     * @param $id
     * @return mixed
     */
    public function getCategoriaById($id)
    {
        return Categoria::findOrFail($id);
    }

    /**
     * Obtiene las categorias en forma de lista
     *
     * @return mixed
     */
    public function getCategoriasList()
    {
        return Categoria::all()
            ->sortBy('descripcion')
            ->lists('descripcion', 'id');
    }

    /**
     * Obtiene una propiedad por su id
     *
     * @param $id
     * @return mixed
     */
    public function getPropiedadById($id)
    {
        return Propiedad::findOrFail($id);
    }

    /**
     * Obtiene los tipos de propiedad en gorma de lista
     *
     * @return mixed
     */
    public function getPropiedadesList()
    {
        return Propiedad::all()
            ->lists('descripcion', 'id');
    }

    /**
     * Crea un registro de horas mensuales para el almacen
     *
     * @param $idAlmacen
     * @param array $data
     * @return mixed
     */
    public function registraHorasMensuales($idAlmacen, array $data)
    {
        $almacen = $this->getById($idAlmacen);

        $horaMensual = new HoraMensual($data);

        $almacen->horasMensuales()->save($horaMensual);

        return $horaMensual;
    }

}
