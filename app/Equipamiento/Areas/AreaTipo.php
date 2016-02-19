<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Core\Models\Obra;
use Kalnoy\Nestedset\Node;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;

class AreaTipo extends Node
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.areas_tipo';

    protected $fillable = ['nombre', 'descripcion', 'clave'];

    /**
     * Obtiene las areas tipo que son las ultimas en la jerarquia.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyLeafs($query)
    {
        return $query->whereRaw('_rgt - _lft = 1');
    }

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
        return $this->hasMany(MaterialRequerido::class, 'id_tipo_area')->with('material');
    }

    /**
     * Areas asignadas a este tipo de area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas()
    {
        return $this->hasMany(Area::class, 'tipo_id')->orderBy('_lft');
    }

    /**
     * Areas que estan asignadas a este area tipo dentro de otra area.
     * 
     * @param  Area   $area
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function areasAsignadasDentroDe(Area $area)
    {
        return $this->areas()
            ->where('_lft', '>', $area->_lft)
            ->where('_rgt', '<', $area->_rgt)
            ->get();
    }

    /**
     * Obtiene la ruta de esta area tipo.
     * 
     * @return string
     */
    public function getRutaAttribute()
    {
        return $this->ruta(' / ');
    }

    /**
     * Genera la ruta de esta area tipo.
     * 
     * @param  string $separador
     * @return string
     */
    public function ruta($separador = '/')
    {
        return $this->getAncestors()
            ->push($this)
            ->implode('nombre', $separador);
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
     * @param  AreaTipo|null $parent
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
     * @param $id_material
     * @return AreaTipo
     */
    public function agregaArticuloRequerido($id_material)
    {
        $material_requerido = new MaterialRequerido([
            'id_material' => $id_material,
            'cantidad_requerida' => 1,
            'precio_estimado' => 0.0,
            'cantidad_comparativa' => null,
            'precio_comparativa' => null,
            'existe_para_comparativa' => true,
        ]);
        $this->materialesRequeridos()->save($material_requerido);
        return $material_requerido;
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
