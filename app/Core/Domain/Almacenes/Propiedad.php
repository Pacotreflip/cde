<?php namespace Ghi\Core\Domain\Almacenes;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model {

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'maquinaria.propiedades';

    /**
     * @var string
     */
    protected $primaryKey = 'id_propiedad';
} 