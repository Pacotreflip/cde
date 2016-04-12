<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Database\Eloquent\Model;
use Ghi\Core\Models\Obra;
class Almacen extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'almacenes';
    
    protected $primaryKey = 'id_almacen';
    
    public $timestamps = false;

    /**
     * Campos afectables por asignacion masiva
     *
     * @var array
     */
    protected $fillable = ['id_obra', 'descripcion','tipo_almacen', 'virtual'];
    
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }
}
