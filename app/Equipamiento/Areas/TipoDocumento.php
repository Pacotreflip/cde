<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $connection = 'cadeco';
    
    protected $table = 'Equipamiento.tipos_documento';
    
    protected $fillable = ['descripcion'];
    
    public function area_documento(){
        return $this->hasMany(AreaDocumento::class, "id_tipo_documento");
    }
}
