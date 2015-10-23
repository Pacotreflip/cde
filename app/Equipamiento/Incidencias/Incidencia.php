<?php

namespace Ghi\Equipamiento\Incidencias;

use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;

class Incidencia extends Model
{
    use TransaccionTrait;

    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.incidencias';

    protected $fillable = ['fecha_incidencia', 'motivo', 'descripcion', 'anotaciones'];

    
}
