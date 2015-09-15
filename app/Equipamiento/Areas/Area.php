<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\Node;

class Area extends Node
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.areas';

    /**
     * Campos afectables por asignacion masiva
     *
     * @var array
     */
    protected $fillable = ['nombre', 'clave', 'descripcion'];

    /**
     * Subtipo de area
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }

    /**
     * Mueve esta area dentro de otra area al final
     *
     * @param Area $parent
     * @return Area
     */
    public function moverA(Area $parent)
    {
        if (! $this->isChildOf($parent)) {
            $this->appendTo($parent);
        }

        return $this;
    }

    /**
     * Asigna el subtipo a esta area
     *
     * @param Tipo $tipo
     * @return Area
     */
    public function asignaTipo(Tipo $tipo)
    {
        return $this->tipo()->associate($tipo);
    }
}
