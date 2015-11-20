<?php

namespace Ghi\Equipamiento\Transacciones;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Recepciones\ItemRecepcion;

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
     * Indica la cantidad recibida de este item.
     * 
     * @return float
     */
    public function getCantidadRecibidaAttribute()
    {
        return $this->recibido();
    }

    /**
     * Indica la cantidad pendiente por recibir de este item.
     * 
     * @return float
     */
    public function getCantidadPorRecibirAttribute()
    {
        return $this->cantidad - $this->cantidad_recibida;
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
     * Recepciones relacionadas con este item
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entregas()
    {
        return $this->hasMany(ItemRecepcion::class, 'id_item', 'id_item');
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

    /**
     * Identifica si ya se recibio la cantidad total de este item.
     * 
     * @return bool
     */
    public function seRecibioTodo()
    {
        return $this->cantidad - $this->recibido();
    }

    /**
     * Identifica si este item tiene cantidad pendiente por recibir.
     * 
     * @return bool
     */
    public function tieneCantidadPorRecibir()
    {
        return (bool) $this->cantidad_por_recibir;
    }

    /**
     * Identifica si se puede recibir una cantidad de este item.
     * 
     * @param  float $cantidad
     * @return bool
     */
    public function puedeRecibir($cantidad)
    {
        return ($this->cantidad_por_recibir - $cantidad) >= 0;
    }
}
