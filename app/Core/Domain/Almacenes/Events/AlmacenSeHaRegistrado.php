<?php namespace Ghi\Core\Domain\Almacenes\Events;

class AlmacenSeHaRegistrado {

    public $almacen;

    /**
     * @param $almacen
     */
    function __construct($almacen)
    {
        $this->almacen = $almacen;
    }

} 