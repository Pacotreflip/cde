<?php

namespace Ghi\Domain\Core\Obras;

use Ghi\Domain\Almacenes\Almacen;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Obra extends Model
{
    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'obras';

    /**
     * @var string
     */
    protected $primaryKey = 'id_obra';

    /**
     * @var array
     */
    protected $fillable = [
        'nombre', 'descripcion', 'tipo_obra', 'constructora', 'cliente',
        'facturar', 'responsable', 'rfc', 'direccion', 'ciudad', 'codigo_postal',
        'estado', 'id_moneda', 'iva', 'fecha_inicial', 'fecha_final'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = ['fecha_inicial', 'fecha_final'];

    /**
     * @var string
     */
    protected $presenter = ObraPresenter::class;

    /**
     * Almacenes asociados con la obra actual
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function almacenes()
    {
        return $this->hasMany(Almacen::class, 'id_obra', 'id_obra');
    }
}
