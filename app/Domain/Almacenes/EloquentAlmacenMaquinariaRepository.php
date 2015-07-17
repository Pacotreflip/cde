<?php

namespace Ghi\Domain\Almacenes;

use Ghi\Domain\Core\BaseRepository;
use Ghi\Domain\Core\Transaccion;

class EloquentAlmacenMaquinariaRepository extends BaseRepository implements AlmacenMaquinariaRepository
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getAll()
    {
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->orderBy('descripcion', 'asc')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllPaginated($how_many = 30)
    {
        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->orderBy('descripcion', 'asc')
            ->paginate($how_many);
    }

    /**
     * {@inheritdoc}
     */
    protected function getIdsEntradaEquipo($id_empresa)
    {
        return Transaccion::where('id_obra', $this->context->getId())
            ->entradaEquipo()
            ->where('id_empresa', $id_empresa)
            ->lists('id_transaccion')
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function getByIdEmpresa($id_empresa)
    {
        $idsEntradaEquipo = $this->getIdsEntradaEquipo($id_empresa);

        return AlmacenMaquinaria::where('id_obra', $this->context->getId())
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->whereHas('equipos.item', function ($query) use ($idsEntradaEquipo) {
                $query->whereIn('id_transaccion', $idsEntradaEquipo);
            })
            ->orderBy('descripcion')
            ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getEquipoActivoEnPeriodo($id_almacen, $fecha_inicial, $fecha_final)
    {
        $maquina =  AlmacenMaquinaria::where('id_almacen', $id_almacen)
            ->whereIn('tipo_almacen', [Almacen::TIPO_MAQUINARIA, Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS])
            ->where(function ($query) use ($fecha_inicial, $fecha_final) {
                $query->where('fecha_desde', '<=', $fecha_inicial)
                    ->where(function ($query) use ($fecha_final) {
                        $query->where('fecha_hasta', '>=', $fecha_final)
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
     * {@inheritdoc}
     */
    public function getClasificacionesList()
    {
        return [
            Clasificacion::MAYOR      => new Clasificacion(Clasificacion::MAYOR),
            Clasificacion::MENOR      => new Clasificacion(Clasificacion::MENOR),
            Clasificacion::TRANSPORTE => new Clasificacion(Clasificacion::TRANSPORTE),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getPropiedadesList()
    {
        return [
            Propiedad::PROPIO   => new Propiedad(Propiedad::PROPIO),
            Propiedad::TERCEROS => new Propiedad(Propiedad::TERCEROS),
            Propiedad::SOCIEDAD => new Propiedad(Propiedad::SOCIEDAD),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registraHorasMensuales($id_almacen, array $data)
    {
        $almacen = $this->getById($id_almacen);

        $horaMensual = new HoraMensual($data);

        $almacen->horasMensuales()->save($horaMensual);

        return $horaMensual;
    }
}
