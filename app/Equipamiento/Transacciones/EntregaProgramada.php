<?php

namespace Ghi\Equipamiento\Transacciones;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Autenticacion\User;

class EntregaProgramada extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.entregas_programadas';    

    /**
     * @var array
     */
    protected $dates = ['fecha_entrega'];
     
    /**
     * Item relacionado con esta entrega programada
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    
    protected $fillable = [
        'id_item',
        'cantidad_programada',
        'fecha_entrega',
        'observaciones',
        'id_usuario'
    ];
    
    public function items() {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
    
    public function usuario_registro(){
        return $this->hasOne(User::class,"idusuario", "id_usuario");
    }
}
