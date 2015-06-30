<?php

namespace Ghi\Maquinaria\Domain\Conciliacion\Models;

use Ghi\Core\App\TenantModel;

class ContratoRenta extends TenantModel {

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
     * Orden de renta de este item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordenRenta()
    {
        return $this->belongsTo('Ghi\Maquinaria\Domain\Conciliacion\Models\OrdenRenta', 'id_transaccion', 'id_transaccion');
    }

}