<?php

namespace Ghi\Equipamiento\Articulos;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    /**
     * Conexion default de base de datos
     *
     * @var string
     */
    protected $connection = 'equipamiento';

    /**
     * Nombre de la tabla
     *
     * @var string
     */
    protected $table = 'unidades';

    /**
     * @var string
     */
    protected $primaryKey = 'codigo';

    /**
     * Campos que se pueden asignar masivamente
     *
     * @var array
     */
    protected $fillable = ['codigo'];
}
