<?php

namespace Ghi\Domain\Almacenes;

interface AlmacenMaquinariaRepository
{
    /**
     * Obtiene un almacen por su id
     *
     * @param $id
     * @return AlmacenMaquinaria
     */
    public function getById($id);

    /**
     * Obtiene todos los almacenes de una obra
     *
     * @return \Illuminate\Database\Eloquent\Collection|AlmacenMaquinaria
     */
    public function getAll();

    /**
     * Obtiene todos los almacenes de una obra paginados
     *
     * @param int $how_many
     * @return \Illuminate\Database\Eloquent\Collection|AlmacenMaquinaria
     */
    public function getAllPaginated($how_many = 30);

    /**
     * Busca los almacenes de una empresa a traves de las entradas de equipo
     *
     * @param $id_empresa
     * @return \Illuminate\Database\Eloquent\Collection|AlmacenMaquinaria
     */
    public function getByIdEmpresa($id_empresa);

    /**
     * Obtiene la maquina que entro en un almacen y que esta activa en un periodo de tiempo
     *
     * @param $id_almacen
     * @param $fecha_inicial
     * @param $fecha_final
     * @return AlmacenMaquinaria
     *
     * @throws \Ghi\Domain\Core\Exceptions\ReglaNegocioException
     */
    public function getEquipoActivoEnPeriodo($id_almacen, $fecha_inicial, $fecha_final);

    /**
     * Obtiene las categorias en forma de lista
     *
     * @return array
     */
    public function getClasificacionesList();

    /**
     * Obtiene los tipos de propiedad en gorma de lista
     *
     * @return array
     */
    public function getPropiedadesList();

    /**
     * Crea un registro de horas mensuales para el almacen
     *
     * @param $id_almacen
     * @param array $data
     * @return HoraMensual
     */
    public function registraHorasMensuales($id_almacen, array $data);
}
