<?php

namespace Ghi\Equipamiento\Areas;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\Node;

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
     * Mueve este tipo dentro de otro al final
     *
     * @param Tipo|null $parent
     * @return Tipo
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

    /**
     * Crea un nuevo tipo de area dentro de otro
     *
     * @param array $data
     * @param Tipo|null $parent
     * @return Tipo
     */
    public static function crearDentroDe(array $data, $parent = null)
    {
        return (new static($data))->moverA($parent);
    }
}
