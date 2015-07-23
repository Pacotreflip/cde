<?php

namespace Ghi\Domain\Conciliacion;

use Ghi\Domain\Core\Item;

class ItemParteUso extends Item
{
    const HORA_TRABAJADA  = 0;
    const HORA_ESPERA     = 1;
    const HORA_REPARACION = 2;

    /**
     * @var array
     */
    protected $fillable = [
        'id_almacen',
        'id_concepto',
        'unidad',
        'numero',
        'cantidad',
    ];
}
