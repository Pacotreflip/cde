<?php

namespace Ghi\Equipamiento\Cierres;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Areas\Area;

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
}
