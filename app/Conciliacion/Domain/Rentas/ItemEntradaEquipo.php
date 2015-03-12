<?php namespace Ghi\Maquinaria\Domain\Conciliacion\Models;

use Ghi\Core\App\TenantModel;

class ItemEntradaEquipo extends TenantModel {

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
     * Transaccion de entrada del equipo
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entrada()
    {
        return $this->belongsTo('Ghi\Maquinaria\Domain\Conciliacion\Models\EntradaEquipo', 'id_transaccion', 'id_transaccion');
    }

    /**
     * Almacen en que entro el equipo
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipo()
    {
        return $this->belongsTo('Ghi\SharedKernel\Models\Equipo', 'id_almacen', 'id_almacen');
    }
}