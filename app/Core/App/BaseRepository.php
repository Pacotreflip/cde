<?php  namespace Ghi\Core\App;

use Ghi\Core\Services\Context;
use Illuminate\Config\Repository;

abstract class BaseRepository {

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Repository
     */
    private $config;

    /**
     * Crea una nueva instancia de repositorio
     * estableciendo la base de datos a usar en el contexto actual
     *
     * @param Context $context
     * @param Repository $config
     */
    function __construct(Context $context, Repository $config)
    {
        $this->context = $context;
        $this->config = $config;

        $this->config->set('database.connections.cadeco.database', $this->context->getDatabaseName());
    }
}