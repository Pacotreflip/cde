<?php

namespace Ghi\Equipamiento\Asignaciones;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.asignaciones';

    protected $dates = ['fecha_asignacion'];

    protected $fillable = [];

    public function items()
    {
        return $this->hasMany(ItemAsignacion::class, 'id_asignacion');
    }
}
