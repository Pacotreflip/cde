<?php

namespace Ghi\Equipamiento\Transacciones;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Autenticacion\User;

class PagoProgramado extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.pagos_programados';    

    /**
     * @var array
     */
    protected $dates = ['fecha'];
     
    /**
     * Item relacionado con este pago programado
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    
    protected $fillable = [
        'id_transaccion',
        'fecha',
        'monto',
        'id_usuario',
        'observaciones'
    ];
    
    public function transaccion() {
        return $this->belongsTo(Transaccion::class, 'id_transaccion', 'id_transaccion');
    }
    
    public function usuario_registro(){
        return $this->hasOne(User::class,"idusuario", "id_usuario");
    }
}
