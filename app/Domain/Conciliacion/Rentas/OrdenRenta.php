<?php namespace Ghi\Maquinaria\Domain\Conciliacion\Models;

use Ghi\Core\App\TenantModel;
use Ghi\Maquinaria\Domain\Conciliacion\QueryScopes\OpcionesOrdenRentaTrait;
use Ghi\Maquinaria\Domain\Conciliacion\QueryScopes\TipoTransaccionTrait;
use Ghi\Maquinaria\Domain\Conciliacion\QueryScopes\TransaccionTipoOrdenRentaTrait;

class OrdenRenta extends TenantModel {

    const TIPO_TRANSACCION = 19;

    use TipoTransaccionTrait, OpcionesOrdenRentaTrait;

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
     * Items de esta orden de renta (maquinas a rentar)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('Ghi\Maquinaria\Domain\Conciliacion\Models\ItemOrdenRenta', 'id_transaccion', 'id_transaccion');
    }

    /**
     * Entradas de equipo relacionadas con esta orden de renta
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entradas()
    {
        return $this->hasMany('Ghi\Maquinaria\Domain\Conciliacion\Models\EntradaEquipo', 'id_antecedente', 'id_transaccion');
    }

}