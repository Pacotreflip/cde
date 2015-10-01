<?php

namespace Ghi\Equipamiento\Transacciones;

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

    protected $casts = [
        'cantidad' => 'float',
        'precio_unitario' => 'float'
    ];

    /**
     * @return float
     */
    public function getCantidadRecibidaAttribute()
    {
        return $this->sumaRecepciones();
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
     * Obtiene la sumatoria de recepciones que se han hecho de este item/material.
     * 
     * @return float
     */
    public function sumaRecepciones()
    {
        return (float) \DB::connection($this->connection)
            ->table('Equipamiento.recepciones')
            ->join('Equipamiento.recepciones_materiales', 'Equipamiento.recepciones.id', '=', 'Equipamiento.recepciones_materiales.id_recepcion')
            ->where('id_orden_compra', $this->id_transaccion)
            ->where('id_material', $this->id_material)
            ->sum('cantidad');
    }
}
