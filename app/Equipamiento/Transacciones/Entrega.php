<?php

namespace Ghi\Equipamiento\Transacciones;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Core\Models\Obra;
use Ghi\Core\Models\User;
use Ghi\Equipamiento\Comprobantes\Comprobante;

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
    public function comprobantes() {
        return $this->hasMany(Comprobante::class, 'id_entrega', 'id');
    } 
    public function agregaComprobante(Comprobante $comprobante) {
        return $this->comprobantes()->save($comprobante);
    }
}
