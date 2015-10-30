<?php

namespace Ghi\Equipamiento\Transacciones;

use Ghi\Core\Models\Obra;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Proveedores\Proveedor;

class Transaccion extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'transacciones';

    /**
     * @var string
     */
    protected $primaryKey = 'id_transaccion';

    /**
     * @var array
     */
    protected $dates = ['fecha', 'cumplimiento', 'vencimiento'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Obra relacionada con esta adquisicion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Empresa relacionada con esta adquisicion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Proveedor::class, 'id_empresa', 'id_empresa');
    }

    /**
     * Items relacionados con esta transaccion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'id_transaccion', 'id_transaccion');
    }

    /**
     * Transacciones de tipo orden compra materiales
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenesCompraMateriales($query)
    {
        return $query->where('tipo_transaccion', Tipo::ORDEN_COMPRA)
            ->where('opciones', 1);
    }
}
