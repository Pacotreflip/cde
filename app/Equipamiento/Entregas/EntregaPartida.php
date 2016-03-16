<?php

namespace Ghi\Equipamiento\Entregas;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Cierres\CierrePartida;

class EntregaPartida extends Model
{
    /**
     * @var string
     */
    protected $table = 'Equipamiento.entrega_partidas';

    /**
     * @var array
     */
    protected $fillable = ['id_entrega', 'id_cierre_partida'];
    
    /**
     *
     * @var string 
     */
    protected $connection = 'cadeco';
    
    public function entrega(){
        return $this->belongsTo(Entrega::class, "id_entrega");
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cierre_partida(){
        return $this->belongsTo(CierrePartida::class, "id_cierre_partida");
    }
}
