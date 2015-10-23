<?php

namespace Ghi\Equipamiento\Transacciones;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;

class Item extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var string
     */
    protected $primaryKey = 'id_item';

    /**
     * @var array
     */
    protected $casts = [
        'cantidad' => 'float',
        'precio_unitario' => 'float'
    ];

    /**
     * @return float
     */
    public function getCantidadRecibidaAttribute()
    {
        return $this->recibido();
    }

    /**
     * Material relacionado con este item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    /**
     * Entregas relacionadas con este item
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'id_item', 'id_item');
    }

    /**
     * Obtiene la sumatoria de recepciones que se han hecho de este item.
     * 
     * @return float
     */
    public function recibido()
    {
        return (float) DB::connection($this->connection)
            ->table('Equipamiento.recepciones')
            ->join('Equipamiento.recepcion_items', 'recepciones.id', '=', 'recepcion_items.id_recepcion')
            ->where('id_orden_compra', $this->id_transaccion)
            ->where('id_material', $this->id_material)
            ->sum('cantidad_recibida');
    }
}
