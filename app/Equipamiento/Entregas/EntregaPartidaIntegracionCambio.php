<?php

namespace Ghi\Equipamiento\Entregas;

use Illuminate\Database\Eloquent\Model;

class EntregaPartidaIntegracionCambio extends Model
{
    
    /**
     * @var string
     */
    protected $table = 'Equipamiento.entrega_partidas_integracion_cambio';
    
    /**
     * @var array
     */
    protected $fillable = ['id_entrega', 'descripcion', 'cantidad', 'unidad', 'ubicacion'];
    
    /**
     *
     * @var string 
     */
    protected $connection = 'cadeco';
    
    public function entrega(){
        return $this->belongsTo(Entrega::class, "id_entrega");
    }
}
