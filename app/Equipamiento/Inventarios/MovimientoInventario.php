<?php

namespace Ghi\Equipamiento\Inventarios;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.inventario_movimientos';

    protected $casts = [
        'cantidad_actual' => 'float',
        'cantidad_anterior' => 'float',
    ];

    /**
     * Inventario relacionado con este movimiento.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'id_inventario');
    }
}
