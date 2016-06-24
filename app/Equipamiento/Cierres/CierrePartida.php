<?php

namespace Ghi\Equipamiento\Cierres;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Entregas\EntregaPartida;
class CierrePartida extends Model
{
    /**
     * @var string
     */
    protected $table = 'Equipamiento.cierres_partidas';

    /**
     * @var array
     */
    protected $fillable = ['id_area', 'id_cierre'];
    
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
        return $this->belongsTo(Area::class, "id_area");
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cierre(){
        return $this->belongsTo(Cierre::class, "id_cierre");
    }
    
     /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cierre_partida_asignacion(){
        return $this->hasMany(CierrePartidaAsignacion::class, "id_cierre_partida");
    }
    
    public function entrega_partida(){
        return $this->hasOne(EntregaPartida::class, "id_cierre_partida");
    }
    
    public function cantidad_validada(){
        $cierres_partidas_asignacion = $this->cierre_partida_asignacion;
        $cantidad_validada = 0;
        foreach($cierres_partidas_asignacion as $cierre_partida_asignacion){
            $cantidad_validada+= $cierre_partida_asignacion->asignacion_item_validacion->item_asignacion->cantidad_asignada;
        }
        return($cantidad_validada);
    }
    
    public function getCantidadAttribute(){
        if($this->area->es_almacen === 0){
            return $this->cantidad_validada();
        }else{
            return $this->area->materiales_almacenados->sum("cantidad_existencia");
        }
    }
}
