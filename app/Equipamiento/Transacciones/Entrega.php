<?php

namespace Ghi\Equipamiento\Transacciones;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Core\Models\Obra;
use Ghi\Core\Models\User;

class Entrega extends Model
{
    use TransaccionTrait;
    /**
     * @var string
     */
    protected $table = 'entregas';
    
    /**
     *
     * @var string 
     */
    protected $connection = 'cadeco';
   
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item(){
        return $this->belongsTo(Item::class, "id_item", "id_item");
    }
    public function concepto(){
        return $this->hasOne(\Ghi\Equipamiento\Areas\Concepto::class, "id_concepto", "id_concepto");
    }
}
