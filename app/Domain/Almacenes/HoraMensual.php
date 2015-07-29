<?php

namespace Ghi\Domain\Almacenes;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class HoraMensual extends Model
{
    use PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Maquinaria.horas_mensuales';

    /**
     * @var array
     */
    protected $fillable = [
        'inicio_vigencia',
        'horas_contrato',
        'horas_operacion',
        'horas_programa',
        'observaciones',
        'creado_por'
    ];

    /**
     * @var array
     */
    protected $dates = ['inicio_vigencia'];

    /**
     * @var array
     */
    protected $casts = [
        'horas_contrato'  => 'int',
        'horas_operacion' => 'int',
        'horas_programa'  => 'int',
    ];
    
    /**
     * @var
     */
    protected $presenter = HoraMensualPresenter::class;
}
