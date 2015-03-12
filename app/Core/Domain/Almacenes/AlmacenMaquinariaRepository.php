<?php namespace Ghi\Core\Domain\Almacenes;

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
     * Busca los almacenes de maquinaria de un proveedor
     * a traves de las entradas de equipo en almacen
     *
     * @param $idObra
     * @param $idEmpresa
     * @return mixed
     */
    public function findByIdProveedor($idObra, $idEmpresa);

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
    public function findMaquinaActivaEnPeriodo($idAlmacen, $fechaInicial, $fechaFinal);

    /**
     * Obtiene una lista de categorias
     * @return mixed
     */
    public function getCategoriasList();

    /**
     * Obtiene una lista de propiedades
     * @return mixed
     */
    public function getPropiedadesList();

}