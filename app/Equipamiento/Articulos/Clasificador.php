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
     * Nombre de la tabla
     *
     * @var string
     */
    protected $table = 'articulo_clasificadores';

    /**
     * Campos que se pueden asignar masivamente
     * @var array
     */
    protected $fillable = ['nombre'];
}
