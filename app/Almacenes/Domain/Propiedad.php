<?php namespace Ghi\Almacenes\Domain;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model {

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Maquinaria.propiedades';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

}
