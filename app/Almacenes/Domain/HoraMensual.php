<?php  namespace Ghi\Almacenes\Domain; 

use Illuminate\Database\Eloquent\Model;

class HoraMensual extends Model {

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

}
