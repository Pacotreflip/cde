<?php

namespace Ghi\Equipamiento\Asignaciones\ItemAsignacion;

use Illuminate\Database\Eloquent\Model;

class ItemAsignacion extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.asignacion_items';

    protected $fillable = [];
}
