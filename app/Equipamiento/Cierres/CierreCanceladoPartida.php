<?php

namespace Ghi\Equipamiento\Cierres;
use Illuminate\Database\Eloquent\Model;

class CierreCanceladoPartida extends Model
{
        /**
     * @var string
     */
    protected $table = 'Equipamiento.cierres_cancelados_partidas';

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
        return $this->belongsTo(CierreCancelado::class, "id_cierre");
    }
}
