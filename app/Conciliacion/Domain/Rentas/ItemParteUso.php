<?php namespace Ghi\Maquinaria\Domain\Conciliacion\Models;

use Ghi\Core\App\TenantModel;

class ItemParteUso extends TenantModel {

    const TIPO_HORA_TRABAJADA = 0;
    const TIPO_HORA_ESPERA = 1;
    const TIPO_HORA_REPARACION = 2;

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var string
     */
    protected $primaryKey = 'id_item';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id_almacen',
        'id_concepto',
        'unidad',
        'numero',
        'cantidad',
    ];

}