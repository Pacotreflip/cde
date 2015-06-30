<?php

namespace Ghi\Domain\Core\Usuarios;

interface UserRepository
{
    /**
     * Obtiene un usuario por su id
     *
     * @param $id
     * @return User
     */
    public function getById($id);

    /**
     * Obtiene un usuario por su nombre de usuario
     *
     * @param $nombre
     * @return User
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
     * @return \Illuminate\Database\Eloquent\Collection|Obra
     */
    public function getObras($idUsuario);
}
