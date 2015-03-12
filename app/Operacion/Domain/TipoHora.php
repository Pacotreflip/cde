<?php namespace Ghi\Operacion\Domain;

use Illuminate\Database\Eloquent\Model;

class TipoHora extends Model {

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'maquinaria.tipos_hora';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];
} 