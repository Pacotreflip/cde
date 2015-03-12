<?php  namespace Ghi\Core\Domain;

use Illuminate\Database\Eloquent\Model;

class BaseDatosCadeco extends Model {

    /**
     * @var string
     */
    protected $connection = 'generales';

    /**
     * @var string
     */
    protected $table = 'bases_datos_cadeco';

    /**
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion'];

}