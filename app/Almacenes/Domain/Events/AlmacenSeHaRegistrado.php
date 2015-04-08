<?php namespace Ghi\Almacenes\Domain;

class AlmacenSeHaRegistrado
{
    public $almacen;

    /**
     * @param $almacen
     */
    function __construct($almacen)
    {
        $this->almacen = $almacen;
    }

}
