<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Core\Models\Obra;
use Kalnoy\Nestedset\Node;
use Illuminate\Database\Eloquent\Model;

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
}
