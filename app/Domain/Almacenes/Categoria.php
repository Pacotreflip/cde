<?php

namespace Ghi\Domain\Almacenes;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Maquinaria.categorias';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];
}
