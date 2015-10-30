<?php

namespace Ghi\Equipamiento\Transacciones;

use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;

class ItemTransaccion extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.items_transaccion';

    /**
     * Transaccion relacionada con este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function transaccion()
    {
        return $this->morphTo('transaccion', 'tipo_transaccion', 'id_transaccion');
    }

    /**
     * Material relacionado con este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    /**
     * Area origen de este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function origen()
    {
        return $this->belongsTo(Area::class, 'id_area_origen');
    }

    /**
     * Area destino de este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destino()
    {
        return $this->belongsTo(Area::class, 'id_area_destino');
    }

    /**
     * Crea un nuevo item de transaccion a partir de un material.
     * 
     * @param  Material $material
     * @param  float    $cantidad
     * @param  float    $precio
     * @return self
     */
    public static function nuevoConMaterial(Material $material, $cantidad, $precio = 0.0)
    {
        $item = new static();
        $item->id_material = $material->id_material;
        $item->cantidad = $cantidad;
        $item->precio = $precio;

        return $item;
    }
}
