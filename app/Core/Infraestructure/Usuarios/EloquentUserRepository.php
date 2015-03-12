<?php namespace Ghi\Core\Infraestructure\Usuarios;

use Ghi\Core\Domain\BaseDatosCadeco;
use Ghi\Core\Domain\Obras\Obra;
use Ghi\Core\Domain\Usuarios\UserRepository;
use Ghi\Core\Domain\Usuarios\User;
use Ghi\Core\Domain\Usuarios\UsuarioCadeco;
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
    function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Obtiene un usuario por su id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return User::where('idusuario', $id)->firstOrFail();
    }

    /**
     * Obtiene un usuario por su nombre de usuario
     *
     * @param $nombre
     * @return mixed
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

        return UsuarioCadeco::where('usuario', $usuario->usuario)
            ->first();
    }

    /**
     * Obtiene las obras de un usuario cadeco de todas las bases
     * de datos definidas
     *
     * @param $idUsuario
     * @return mixed
     */
    public function getObras($idUsuario)
    {
        $obrasUsuario = new Collection();

        $basesDatos = BaseDatosCadeco::all();

        foreach ($basesDatos as $bd)
        {
            $this->config->set('database.connections.cadeco.database', $bd->nombre);

            $usuarioCadeco = $this->getUsuarioCadeco($idUsuario);

            $obras = $usuarioCadeco->obras();

            foreach ($obras as $obra)
            {
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
     * @param $usuarioCadeco
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getObrasUsuario($usuarioCadeco)
    {
        if ( ! $usuarioCadeco)
        {
            return [];
        }

        if ($usuarioCadeco->tieneAccesoATodasLasObras())
        {
            return Obra::all();
        }

        return $usuarioCadeco->obras;
    }
} 