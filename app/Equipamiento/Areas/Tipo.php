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
    protected $fillable = ['nombre', 'descripcion', 'clave'];

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
     * Materiales requeridos para este tipo de area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materialesRequeridos()
    {
        return $this->hasMany(MaterialRequerido::class, 'id_tipo_area');
    }

    /**
     * Materiales requeridos para este tipo de area
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function materiales()
    // {
    //     return $this->belongsToMany(Material::class, 'Equipamiento.materiales_requeridos', 'id_tipo_area', 'id_material')
    //         ->orderBy('descripcion')
    //         ->withTimestamps()
    //         ->withPivot('cantidad_requerida', 'precio_estimado', 'se_evalua', 'cantidad_comparativa', 'precio_comparativa', 'existe_para_comparativa');
    // }

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
        return $this->materialesRequeridos->count();
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
        return $this->materialesRequeridos->sum('precio_estimado');
    }

    /**
     * Crea un nuevo tipo de area dentro de otro.
     *
     * @param array $data
     * @return self
     */
    public static function nuevo(array $data)
    {
        return new static($data);
    }

    /**
     * Relaciona este tipo de area con una obra.
     * 
     * @param  Obra $obra
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
     * @param  Tipo|null $parent
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
     * Agrega materiales requeridos a este tipo de area.
     *
     * @param  array|int $material
     * @param int $cantidad_requerida
     * @param float $precio_estimado
     * @param int $cantidad_comparativa
     * @param float $precio_comparativa
     * @param bool $existe_para_comparativa
     * @return Tipo
     */
    public function agregaArticuloRequerido($id_material)
    {
        $this->materialesRequeridos()->create([
            'id_material' => $id_material,
            'cantidad_requerida' => 1,
            'precio_estimado' => 0.0,
            'cantidad_comparativa' => null,
            'precio_comparativa' => null,
            'existe_para_comparativa' => true,
        ]);

        return $this;
    }

    /**
     * Elimina materiales requeridos.
     * 
     * @param array $ids
     */
    public function quitaMaterialesRequeridos(array $ids)
    {
        foreach ($ids as $id_articulo) {
            $this->materialesRequeridos->find($id_articulo)->delete();
        }
    }
}
