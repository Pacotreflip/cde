<?php

namespace Ghi\Equipamiento\Articulos;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    const TIPO_GENERICA = 0;
    const TIPO_MASA_VOLUMEN = 1;
    const TIPO_TIEMPO_GENERICA = 2;
    const TIPO_TIEMPO_DIA = 10;
    const TIPO_TIEMPO_HORA = 6;
    const TIPO_USO = 3;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'unidades';

    /**
     * @var string
     */
    // protected $primaryKey = 'unidad';

    /**
     * @var array
     */
    protected $fillable = ['unidad', 'descripcion'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Crea una nueva unidad
     *
     * @param string $unidad
     * @param string $descripcion
     * @return Unidad
     */
    public static function creaUnidad($unidad, $descripcion, $tipo = self::TIPO_GENERICA)
    {
        $unidad = new static(['unidad' => $unidad, 'descripcion' => $descripcion]);
        $unidad->tipo_unidad = $tipo;
        $unidad->save();
        return $unidad;
    }
}
