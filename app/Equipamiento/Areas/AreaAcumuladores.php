<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Database\Eloquent\Model;

class AreaAcumuladores extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.areas_acumuladores';
    
    public function area(){
        return $this->belongsTo(Area::class, "id_area");
    }
}
