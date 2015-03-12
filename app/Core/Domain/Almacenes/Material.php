<?php namespace Ghi\Core\Domain\Almacenes;

use Illuminate\Database\Eloquent\Model;

class Material extends Model {

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'materiales';

    /**
     * @var string
     */
    protected $primaryKey = 'id_material';

    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'tipo_material'];

    /**
     * @var bool
     */
    public $timestamps = false;
} 