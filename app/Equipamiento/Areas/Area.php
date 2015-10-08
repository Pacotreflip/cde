<?php

namespace Ghi\Equipamiento\Areas;

use Kalnoy\Nestedset\Node;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Inventarios\Exceptions\InventarioNoEncontradoException;

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
     * Inventarios relacionados con esta area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_area');
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

    /**
     * Obtiene un inventario de un material en esta area.
     * 
     * @param  Material $material
     * @throws InventarioNoEncontradoException
     * @return \Ghi\Equipamiento\Inventarios\Inventario
     */
    public function getInventarioDeMaterial(Material $material)
    {
        $inventario = $this->inventarios()->where('id_material', $material->id_material)->first();

        if (! $inventario) {
            throw new InventarioNoEncontradoException;
        }

        return $inventario;
    }
}
