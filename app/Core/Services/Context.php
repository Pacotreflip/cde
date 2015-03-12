<?php namespace Ghi\Core\Services;

interface Context {

    /**
     * Set the database name for the current context
     * @param $name
     * @return mixed
     */
    public function setDatabaseName($name);

    /**
     * Get the database name of the current context
     * @return mixed
     */
    public function getDatabaseName();

    /**
     * Set the id value filter data in the current context
     * @param $id
     * @return mixed
     */
    public  function setId($id);

    /**
     * Get the tenant id value for the current context
     * @return mixed
     */
    public function getId();

    /**
     * Tells if the context is set
     * @return boolean
     */
    public function isEstablished();

    /**
     * Tells if the context is not set
     *
     * @return boolean
     */
    public function notEstablished();

}