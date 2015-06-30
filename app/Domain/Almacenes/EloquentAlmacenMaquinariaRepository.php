<?php

namespace Ghi\Domain\Almacenes;

use Ghi\Domain\Core\BaseRepository;
use Ghi\Domain\Core\Transaccion;

class EloquentAlmacenMaquinariaRepository extends BaseRepository implements AlmacenMaquinariaRepository
{
    /**
     * Obtiene un almacen por su id, incluyendo los
     * equipos que han entrado
     *
     * @param $id
     * @return AlmacenMaquinaria
     */
    public function getById($id)
    {
        return AlmacenMaquinaria::with(['equipos' => function ($query) {
            $query->orderBy('fecha_desde', 'ASC');
        }])->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->where('id_almacen', $id)
            ->firstOrFail();
    }

    /**
     * Obtiene todos los almacenes de una obra
     *
     * @return \Illuminate\Database\Eloquent\Collection|AlmacenMaquinaria
     */
    public function getAll()
    {
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->orderBy('descripcion', 'asc')
            ->get();
    }

    /**
     * Obtiene todos los almacenes de una obra paginados
     *
     * @param int $howMany
     * @return \Illuminate\Database\Eloquent\Collection|AlmacenMaquinaria
     */
    public function getAllPaginated($howMany = 50)
    {
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->orderBy('descripcion', 'asc')
            ->paginate($howMany);
    }

    /**
     * Obtiene los ids de transaccion de las entradas de equipo de una empresa
     *
     * @param $idEmpresa
     * @return array
     */
    protected function getIdsEntradaEquipo($idEmpresa)
    {
        return Transaccion::where('id_obra', $this->context->getId())
            ->entradaEquipo()
            ->where('id_empresa', $idEmpresa)
            ->lists('id_transaccion')
            ->all();
    }

    /**
     * Busca los almacenes de una empresa a traves de las entradas de equipo
     *
     * @param $idEmpresa
     * @return \Illuminate\Database\Eloquent\Collection|AlmacenMaquinaria
     */
    public function getByIdEmpresa($idEmpresa)
    {
        $idsEntradaEquipo = $this->getIdsEntradaEquipo($idEmpresa);

        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->whereHas('equipos.item', function ($query) use ($idsEntradaEquipo) {
                $query->whereIn('id_transaccion', $idsEntradaEquipo);
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
     * @return AlmacenMaquinaria
     * @throws ReglaNegocioException
     */
    public function getEquipoActivoEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal)
    {
        $maquina =  AlmacenMaquinaria::where('id_almacen', $idAlmacen)
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->where(function ($query) use ($fechaInicial, $fechaFinal) {
                $query->where('fecha_desde', '<=', $fechaInicial)
                    ->where(function ($query) use ($fechaFinal) {
                        $query->where('fecha_hasta', '>=', $fechaFinal)
                            ->orWhereNull('fecha_hasta');
                    });
            })
            ->first();

        if (! $maquina) {
            throw new ReglaNegocioException('No existe una maquina activa en el periodo indicado para este almacen.');
        }

        return $maquina;
    }

    /**
     * Obtiene una categoria por su id
     *
     * @param $id
     * @return Categoria
     */
    public function getCategoriaById($id)
    {
        return Categoria::findOrFail($id);
    }

    /**
     * Obtiene las categorias en forma de lista
     *
     * @return array
     */
    public function getCategoriasList()
    {
        return Categoria::all()
            ->sortBy('descripcion')
            ->lists('descripcion', 'id')
            ->all();
    }

    /**
     * Obtiene una propiedad por su id
     *
     * @param $id
     * @return Propiedad
     */
    public function getPropiedadById($id)
    {
        return Propiedad::findOrFail($id);
    }

    /**
     * Obtiene los tipos de propiedad en gorma de lista
     *
     * @return array
     */
    public function getPropiedadesList()
    {
        return Propiedad::all()
            ->lists('descripcion', 'id')
            ->all();
    }

    /**
     * Crea un registro de horas mensuales para el almacen
     *
     * @param $idAlmacen
     * @param array $data
     * @return HoraMensual
     */
    public function registraHorasMensuales($idAlmacen, array $data)
    {
        $almacen = $this->getById($idAlmacen);

        $horaMensual = new HoraMensual($data);

        $almacen->horasMensuales()->save($horaMensual);

        return $horaMensual;
    }
}
