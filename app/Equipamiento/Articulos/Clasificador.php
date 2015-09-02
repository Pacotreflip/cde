<?php

namespace Ghi\Equipamiento\Articulos;

use Illuminate\Database\Eloquent\Model;

class Clasificador extends Model
{
    /**
     * Conexion default de base de datos
     *
     * @var string
     */
    protected $connection = 'equipamiento';

    /**
     * Campos que se pueden asignar masivamente
     * @var array
     */
    protected $fillable = ['nombre'];
}
