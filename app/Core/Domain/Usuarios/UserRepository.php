<?php namespace Ghi\Core\Domain\Usuarios;

interface UserRepository
{
    /**
     * Obtiene un usuario por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * Obtiene un usuario por su nombre de usuario
     *
     * @param $nombre
     * @return mixed
     */
    public function getByNombreUsuario($nombre);

    /**
     * Obtiene el usuario cadeco asociado al usuario de intranet
     *
     * @param $idUsuario
     * @return UsuarioCadeco
     */
    public function getUsuarioCadeco($idUsuario);

    /**
     * Obtiene las obras de un usuario cadeco
     *
     * @param $idUsuario
     * @return mixed
     */
    public function getObras($idUsuario);

}