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
        return $this->belongsToMany(Material::class, 'Equipamiento.requerimientos', 'id_tipo_area', 'id_material')
            ->orderBy('descripcion')
            ->withPivot('cantidad');
    }

    /**
     * Crea un nuevo tipo de area dentro de otro
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
     * Relaciona este tipo de area con una obra
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
     * Mueve este tipo dentro de otro al final
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
     * Agrega articulos requeridos a este tipo de area
     * 
     * @param  array|int  $material
     * @return self
     */
    public function requiereArticulo($material = [])
    {
        $this->materiales()->attach($material, ['cantidad' => 1]);

        return $this;
    }
}
