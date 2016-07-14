<?php

namespace Ghi\Equipamiento\Transacciones;

use Illuminate\Database\Eloquent\Model;

class EntregasProgramadas extends Model
{
    protected $connection = 'cadeco';
    protected $table = 'Equipamiento.entregas_programadas';    
    
    public function items() {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}
