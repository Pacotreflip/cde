<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Transacciones\Item;

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
    
    public function cantidadRecibidaAcumulada(){
        $data = DB::connection($this->connection)->select("SELECT SUM(cantidad_recibida) AS acumulado FROM(
            SELECT cantidad_recibida, id from Equipamiento.recepcion_items
            WHERE id_item = (SELECT id_item from Equipamiento.recepcion_items WHERE id = $this->id)) AS tabla
        WHERE id <= $this->id"); 
        return $data[0]->acumulado;
    }
    
    public function cantidadPendiente() {
        return $this->item->cantidad - $this->cantidadRecibidaAcumulada();
    }
    
    public function item() {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
    
    public function cantidadRecibidaAnterior() {
        $data = DB::connection($this->connection)->select("SELECT SUM(cantidad_recibida) AS anterior FROM(
            SELECT cantidad_recibida, id from Equipamiento.recepcion_items
            WHERE id_item = (SELECT id_item from Equipamiento.recepcion_items WHERE id = $this->id)) AS tabla
        WHERE id < $this->id"); 
        return $data[0]->anterior > 0 ? $data[0]->anterior: 0;
    }
}
