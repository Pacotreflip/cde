<?php

namespace Ghi\Equipamiento\Cierres;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Asignaciones\AsignacionItemsValidados;

class CierrePartidaAsignacion extends Model
{
    /**
     * @var string
     */
    protected $table = 'Equipamiento.cierres_partidas_asignaciones';

    /**
     * @var array
     */
    protected $fillable = ['id_cierre_partida', 'id_asignacion_item_validacion'];
    
    /**
     *
     * @var string 
     */
    protected $connection = 'cadeco';
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area(){
        return $this->belongsTo(AsignacionItemsValidados::class, "id_asignacion_item_validacion");
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cierre_partida(){
        return $this->belongsTo(CierrePartida::class, "id_cierre_partida");
    }
}
