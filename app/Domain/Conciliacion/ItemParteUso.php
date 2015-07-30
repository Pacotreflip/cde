<?php

namespace Ghi\Domain\Conciliacion;

use Ghi\Domain\Core\Conceptos\Concepto;
use Ghi\Domain\Core\Item;

class ItemParteUso extends Item
{
    const TRABAJADA  = 0;
    const ESPERA     = 1;
    const REPARACION = 2;

    /**
     * @var array
     */
    protected $fillable = [
        'id_almacen',
        'id_concepto',
        'unidad',
        'numero',
        'cantidad',
        'referencia',
        'anticipo',
        'id_material',
        'precio_unitario',
    ];

    /**
     * Concepto relacionado con este item de parte de uso
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'id_concepto', 'id_concepto');
    }

    /**
     * Indica si este item aplica para costo
     *
     * @return bool
     */
    public function aplicaParaCosto()
    {
        return $this->numero == ItemParteUso::TRABAJADA || $this->numero == ItemParteUso::ESPERA;
    }
}
