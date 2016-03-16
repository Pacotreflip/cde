<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Database\Eloquent\Model;

class AreaDocumento extends Model
{
    protected $connection = 'cadeco';
    
    protected $table = 'Equipamiento.area_documentos';
    
    public function area(){
        return $this->belongsTo(Area::class, "id_area");
    }
    
    public function tipo_documento(){
        return $this->belongsTo(TipoDocumento::class, "id_tipo_documento");
    }
}
