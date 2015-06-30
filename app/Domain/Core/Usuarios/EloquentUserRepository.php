<?php

namespace Ghi\Domain\Core\Usuarios;

use Ghi\Domain\Core\BaseDatosCadeco;
use Ghi\Domain\Core\Obras\Obra;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;

class EloquentUserRepository implements UserRepository
{
    /**
     * @var Repository
     */
    private $config;

    /**
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Obtiene un usuario por su id
     *
     * @param $id
     * @return User
     */
    public function getById($id)
    {
        return User::where('idusuario', $id)->firstOrFail();
    }

    /**
     * Obtiene un usuario por su nombre de usuario
     *
     * @param $nombre
     * @return User
     */
    public function getByNombreUsuario($nombre)
    {
        return User::where('usuario', $nombre)->firstOrFail();
    }

    /**
     * Obtiene el usuario cadeco asociado al usuario de intranet
     *
     * @param $idUsuario
     * @return UsuarioCadeco
     */
    public function getUsuarioCadeco($idUsuario)
    {
        $usuario = $this->getById($idUsuario);

        return UsuarioCadeco::find($usuario->usuario);
    }

    /**
     * Obtiene las obras de un usuario cadeco de todas las bases de datos definidas
     *
     * @param $idUsuario
     * @return Collection|Obra
     */
    public function getObras($idUsuario)
    {
        $obrasUsuario = new Collection();

        $basesDatos = BaseDatosCadeco::where('activa', true)->get();

        foreach ($basesDatos as $bd) {
            $this->config->set('database.connections.cadeco.database', $bd->nombre);

            $usuarioCadeco = $this->getUsuarioCadeco($idUsuario);

            $obras = $this->getObrasUsuario($usuarioCadeco);

            foreach ($obras as $obra) {
                $obra->databaseName = $bd->nombre;

                $obrasUsuario->push($obra);
            }

            \DB::disconnect('cadeco');
        }

        return $obrasUsuario->sortBy('nombre');
    }

    /**
     * Obtiene las obras de un usuario cadeco
     *
     * @param UsuarioCadeco $usuarioCadeco
     * @return \Illuminate\Database\Eloquent\Collection|Obra
     */
    private function getObrasUsuario($usuarioCadeco)
    {
        if (! $usuarioCadeco) {
            return [];
        }

        if ($usuarioCadeco->tieneAccesoATodasLasObras()) {
            return Obra::all();
        }

        return $usuarioCadeco->obras;
    }
}
