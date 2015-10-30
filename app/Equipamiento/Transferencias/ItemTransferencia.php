<?php

namespace Ghi\Equipamiento\Transferencias;

use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;

class ItemTransferencia extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.transferencia_items';

    /**
     * Transferencia de este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transferencia()
    {
        return $this->belongsTo(Transferencia::class, 'id_transferencia');
    }

    /**
     * Material de este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material');
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
}
