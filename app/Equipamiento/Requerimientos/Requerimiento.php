<?php

namespace Ghi\Equipamiento\Requerimientos;

use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.requerimientos';

    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'descripcion_larga', 'numero_parte', 'codigo_externo', ];
}