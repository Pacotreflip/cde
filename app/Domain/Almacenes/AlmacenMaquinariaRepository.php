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
     * @param int $howMany
     * @return \Illuminate\Database\Eloquent\Collection|AlmacenMaquinaria
     */
    public function getAllPaginated($howMany = 30);

    /**
     * Busca los almacenes de una empresa a traves de las entradas de equipo
     *
     * @param $idEmpresa
     * @return \Illuminate\Database\Eloquent\Collection|AlmacenMaquinaria
     */
    public function getByIdEmpresa($idEmpresa);

    /**
     * Obtiene la maquina que entro en un almacen y que esta activa en un periodo de tiempo
     *
     * @param $idAlmacen
     * @param $fechaInicial
     * @param $fechaFinal
     * @return AlmacenMaquinaria
     *
     * @throws \Ghi\Domain\Core\Exceptions\ReglaNegocioException
     */
    public function getEquipoActivoEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene una categoria por su id
     *
     * @param $id
     * @return Categoria
     */
    public function getCategoriaById($id);

    /**
     * Obtiene las categorias en forma de lista
     *
     * @return array
     */
    public function getCategoriasList();

    /**
     * Obtiene una propiedad por su id
     *
     * @param $id
     * @return Propiedad
     */
    public function getPropiedadById($id);

    /**
     * Obtiene los tipos de propiedad en gorma de lista
     *
     * @return array
     */
    public function getPropiedadesList();

    /**
     * Crea un registro de horas mensuales para el almacen
     *
     * @param $idAlmacen
     * @param array $data
     * @return mixed
     */
    public function registraHorasMensuales($idAlmacen, array $data);
}
