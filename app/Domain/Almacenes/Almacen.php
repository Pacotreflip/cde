<?php

namespace Ghi\Domain\Almacenes;

use Ghi\Domain\Core\Obras\Obra;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Almacen extends Model
{
    use PresentableTrait;

    const TIPO_MATERIALES = 0;
    const TIPO_MAQUINARIA = 1;
    const TIPO_MAQUINARIA_CONTROL_INSUMOS = 2;
    const TIPO_MANO_OBRA = 3;
    const TIPO_SERVICIOS = 4;
    const TIPO_HERRAMIENTAS = 5;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'almacenes';

    /**
     * @var string
     */
    protected $primaryKey = 'id_almacen';

    /**
     * @var array
     */
    protected $fillable = ['descripcion'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Obra relacionada con este almacen
     *
     * @return mixed
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Registra un nuevo almacen de maquinaria (control de insumos)
     * @param $descripcion
     * @param $numero_economico
     * @return static
     */
//    public static function registro($descripcion, $numero_economico)
//    {
//        $almacen = new static(compact('descripcion', 'numero_economico'));
//
//        $almacen->tipo_almacen = Almacen::TIPO_MAQUINARIA_CONTROL_INSUMOS;
//
//        $almacen->raise(new AlmacenSeHaRegistrado($almacen));
//
//        return $almacen;
//    }
}
