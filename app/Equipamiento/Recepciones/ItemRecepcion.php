<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;

class ItemRecepcion extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.recepcion_items';

    /**
     * Recepcion a la que pertenece este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class, 'id_recepcion');
    }

    /**
     * Material recibido en este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    /**
     * Area donde se almaceno el material de este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area_almacenamiento');
    }
}
