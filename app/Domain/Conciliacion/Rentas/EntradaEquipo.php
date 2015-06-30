<?php

namespace Ghi\Domain\Conciliacion\Rentas;

use Ghi\Core\App\TenantModel;
use Ghi\Maquinaria\Domain\Conciliacion\QueryScopes\OpcionesEntradaEquipoTrait;
use Ghi\Maquinaria\Domain\Conciliacion\QueryScopes\TipoTransaccionTrait;

class EntradaEquipo extends TenantModel {

    const TIPO_TRANSACCION = 33;

    use TipoTransaccionTrait, OpcionesEntradaEquipoTrait;

    /**
     * @var string
     */
    protected $table = 'transacciones';

    /**
     * @var string
     */
    protected $primaryKey = 'id_transaccion';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = ['fecha', 'cumplimiento', 'vencimiento'];

    /**
     * Orden de renta de esta entrada de equipo
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordenRenta()
    {
        return $this->belongsTo('Ghi\Maquinaria\Domain\Conciliacion\Models\OrdenRenta', 'id_antecedente', 'id_transaccion');
    }

    /**
     * Items de esta entrada de equipo (maquinas que entran a un almacen)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('Ghi\Maquinaria\Domain\Conciliacion\Models\ItemEntradaEquipo', 'id_transaccion', 'id_transaccion');
    }

    /**
     * Empresa relacionada con esta entrada de equipo
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedor()
    {
        return $this->belongsTo('Ghi\Maquinaria\Domain\Conciliacion\Models\Proveedor', 'id_empresa', 'id_empresa');
    }
}