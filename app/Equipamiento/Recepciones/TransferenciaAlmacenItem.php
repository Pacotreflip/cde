<?php

namespace Ghi\Equipamiento\Recepciones;

use Illuminate\Database\Eloquent\Model;

class TransferenciaAlmacenItem extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var string
     */
    protected $primaryKey = 'id_item';

    /**
     * @var array
     */
    protected $casts = [
        'cantidad' => 'float',
        'precio_unitario' => 'float'
    ];
    
    public function transferencia_almacen()
    {
        return $this->belongsTo(TransferenciaAlmacen::class, 'id_transaccion', 'id_transaccion');
    }
}
