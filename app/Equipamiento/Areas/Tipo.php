<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Core\Models\Obra;
use Kalnoy\Nestedset\Node;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;

class Tipo extends Node
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.area_tipos';

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Obra relacionada con este tipo de area
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Materiales requeridos para este tipo de area
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'Equipamiento.materiales_requeridos', 'id_tipo_area', 'id_material')
            ->orderBy('descripcion')
            ->withTimestamps()
            ->withPivot('cantidad_requerida', 'costo_estimado', 'se_evalua');
    }

    /**
     * Areas asignadas a este tipo de area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas()
    {
        return $this->hasMany(Area::class, 'tipo_id');
    }

    /**
     * Numero de materiales requeridos para este tipo de area.
     * 
     * @return int
     */
    public function conteoMateriales()
    {
        return $this->materiales->count();
    }

    /**
     * Numero de areas asociadas con este tipo de area.
     * 
     * @return int
     */
    public function conteoAreas()
    {
        return $this->areas->count();
    }

    /**
     * Costo total estimado de este tipo de area.
     * 
     * @return float
     */
    public function costoEstimado()
    {
        return $this->materiales->sum('pivot.costo_estimado');
    }

    /**
     * Crea un nuevo tipo de area dentro de otro.
     *
     * @param array $data
     * @param Tipo|null $parent
     * @return self
     */
    public static function nuevo(array $data)
    {
        return new static($data);
    }

    /**
     * Relaciona este tipo de area con una obra.
     * 
     * @param  Obra   $obra
     * @return self
     */
    public function enObra(Obra $obra)
    {
        $this->obra()->associate($obra);
        return $this;
    }

    /**
     * Mueve este tipo dentro de otro al final.
     * 
     * @param  Tipo|null   $parent
     * @return self
     */
    public function dentroDe($parent = null)
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

    /**
     * Agrega articulos requeridos a este tipo de area.
     * 
     * @param  array|int  $material
     * @return self
     */
    public function requiereArticulo($material, $cantidad_requerida = 1, $costo_estimado = 0)
    {
        if (is_array($material)) {
            $this->materiales()->sync($material);
        } else {
            $this->materiales()->attach($material, [
                'cantidad_requerida' => $cantidad_requerida,
                'costo_estimado' => $costo_estimado
            ]);
        }

        return $this;
    }
}
