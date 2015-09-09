<?php

namespace Ghi\Equipamiento\Articulos;

use Kalnoy\Nestedset\Node;
use Illuminate\Database\Eloquent\Model;

class Clasificador extends Node
{
    /**
     * Conexion default de base de datos
     *
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * Nombre de la tabla
     *
     * @var string
     */
    protected $table = 'Equipamiento.material_clasificadores';

    /**
     * Campos que se pueden asignar masivamente
     * @var array
     */
    protected $fillable = ['nombre'];

    /**
     * Mueve este clasificador dentro de otro
     * 
     * @param  Clasificador|null $parent
     * @return Clasificador
     */
    public function moverA($parent = null)
    {
        if (! $parent) {
            $this->makeRoot();
            return $this;
        }

        if (! $this->isChildOf($parent)) {
            $this->appendTo($parent);
        }

        return $this;
    }
}
