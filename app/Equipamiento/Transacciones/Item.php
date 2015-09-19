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
}
