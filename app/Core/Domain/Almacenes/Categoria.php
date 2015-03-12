<?php namespace Ghi\Core\Domain\Almacenes;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model {

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'maquinaria.categorias';

    /**
     * @var string
     */
    protected $primaryKey = 'id_categoria';

} 