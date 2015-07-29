<?php

namespace Ghi\Domain\Core;

use Ghi\Domain\Core\Obras\Obra;
use Illuminate\Session\Store;

class ContextSession implements Context
{
    /**
     * @var
     */
    private $session;

    /**
     * @param Store $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Set the database name for the current context
     * @param $name
     * @return mixed
     */
    public function setDatabaseName($name)
    {
        $this->session->put('database_name', $name);
    }

    /**
     * Get the database name of the current context
     * @return mixed
     */
    public function getDatabaseName()
    {
        return $this->session->get('database_name');
    }

    /**
     * Sets the id value filter data in the current context
     * @param $id
     * @return mixed
     */
    public function setId($id)
    {
        $this->session->put('id', $id);
    }

    /**
     * Establece la obra del contexto actual
     *
     * @param Obra $obra
     */
    public function setObra(Obra $obra)
    {
        $this->session->put('obra', $obra);
    }

    /**
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->session->get('id');
    }

    /**
     * Tells if the context is set
     *
     * @return boolean
     */
    public function isEstablished()
    {
        return $this->getDatabaseName() && $this->getId();
    }

    /**
     * Tells if the context is not set
     *
     * @return boolean
     */
    public function notEstablished()
    {
        return ! $this->getDatabaseName() && ! $this->getId();
    }
}
