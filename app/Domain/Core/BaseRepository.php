<?php

namespace Ghi\Domain\Core;

use Illuminate\Config\Repository;

abstract class BaseRepository
{
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
    public function __construct(Context $context, Repository $config)
    {
        $this->context = $context;
        $this->config = $config;
    }
}
