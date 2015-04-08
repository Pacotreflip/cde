<?php namespace Ghi\Almacenes\Domain;

use Ghi\Core\App\Exceptions\ReglaNegocioException;

interface AlmacenMaquinariaRepository
{
    /**
     * Obtiene un almacen por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Obtiene todos los almacenes de una obra
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Obtiene todos los almacenes de una obra paginados
     *
     * @param int $howMany
     * @return mixed
     */
    public function getAllPaginated($howMany = 30);

    /**
     * Busca los almacenes de una empresa a traves de las entradas de equipo
     *
     * @param $idEmpresa
     * @return mixed
     */
    public function getByIdEmpresa($idEmpresa);

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
    public function getEquipoActivoEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene una categoria por su id
     *
     * @param $id
     * @return mixed
     */
    public function getCategoriaById($id);

    /**
     * Obtiene las categorias en forma de lista
     *
     * @return mixed
     */
    public function getCategoriasList();

    /**
     * Obtiene una propiedad por su id
     *
     * @param $id
     * @return mixed
     */
    public function getPropiedadById($id);

    /**
     * Obtiene los tipos de propiedad en gorma de lista
     *
     * @return mixed
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
