<?php

namespace Ghi\Domain\Almacenes;

use Ghi\Domain\Core\BaseRepository;
use Ghi\Domain\Core\Exceptions\ReglaNegocioException;
use Ghi\Domain\Core\Inventario;
use Ghi\Domain\Core\Item;
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

    protected function getIdItemEntradaEquipoActivo($id_empresa, $id_almacen, $fecha_inicial, $fecha_final)
    {
        return Inventario::where('id_almacen', $id_almacen)
            ->whereNotNull('referencia')
            ->where(function ($query) use ($fecha_inicial, $fecha_final) {
                $query->where('fecha_desde', '<=', $fecha_inicial)
                    ->where(function ($query) use ($fecha_final) {
                        $query->where('fecha_hasta', '>=', $fecha_final)
                            ->orWhereNull('fecha_hasta');
                    });
            })
            ->whereHas('item.transaccion', function ($query) use ($id_empresa) {
                // El registro en el inventario debe tener un item relacionado con
                // una transaccion de entrada de equipo de la empresa
                $query->where('id_empresa', $id_empresa);
            })
            ->orderBy('fecha_desde', 'DESC')
            ->lists('id_item')
            ->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getEquipoActivo($id_empresa, $id_almacen, $fecha_inicial, $fecha_final)
    {
        $id_item = $this->getIdItemEntradaEquipoActivo($id_empresa, $id_almacen, $fecha_inicial, $fecha_final);

        $equipo = Inventario::where('id_almacen', $id_almacen)
            ->whereNotNull('referencia')
            ->where('id_item', $id_item)
            ->first();

        if (! $equipo) {
            throw new ReglaNegocioException('No existe un equipo activo en el periodo indicado para esta empresa.');
        }
        return $equipo;
    }

    /**
     * {@inheritdoc}
     */
    public function getContratoVigente($id_empresa, $id_almacen, $fecha_inicial, $fecha_final)
    {
        $equipo = $this->getEquipoActivo($id_empresa, $id_almacen, $fecha_inicial, $fecha_final);

        $item_entrada = $equipo->item;
        $item_renta   = $item_entrada->itemAntecedente;

        if (! $item_renta) {
            throw new ReglaNegocioException('No existe un contrato de renta para este periodo.');
        }
        return $item_renta;
    }
}
