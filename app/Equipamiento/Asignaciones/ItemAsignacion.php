<?php

namespace Ghi\Equipamiento\Asignaciones;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;
class ItemAsignacion extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.asignacion_items';

    protected $fillable = [];
    
    /**
     * Recepcion a la que pertenece este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'id_asignacion');
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
    public function area_origen()
    {
        return $this->belongsTo(Area::class, 'id_area_origen');
    }
    
    /**
     * Area donde se almaceno el material de este item.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area_destino()
    {
        return $this->belongsTo(Area::class, 'id_area_destino');
    }
    
    public function material_requerido(){
        return $this->belongsTo(MaterialRequeridoArea::class, "id_material_requerido");
    }
    
    public function validacion(){
        return $this->hasOne(AsignacionItemsValidados::class, "id_item_validacion", "id");
    }
    
}
