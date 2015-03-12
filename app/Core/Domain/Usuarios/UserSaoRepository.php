<?php namespace Ghi\Core\Domain\Usuarios;

interface UserSaoRepository
{
    /**
     * Obtiene un usuario del sao por el nombre de usuario
     *
     * @param $username
     * @return mixed
     */
    public function findByUsername($username);
}