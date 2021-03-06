<?php

namespace Ghi\Equipamiento\Areas;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Moneda;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Asignaciones\ItemAsignacion;
use Ghi\Equipamiento\Asignaciones\AsignacionItemsValidados;
class MaterialRequeridoArea extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.materiales_requeridos_area';

    protected $fillable = [
        'id_material',
        'id_material_requerido',
        'cantidad_requerida',
        'cantidad_comparativa',
        'existe_para_comparativa',
    ];

    protected $casts = [
        'existe_para_comparativa' => 'bool',
        'cantidad_requerida' => 'int',
        'cantidad_comparativa' => 'int',
    ];

    /**
     * Calcula el importe de este material requerido.
     * 
     * @return float
     */
    public function getImporteAttribute()
    {
        return $this->cantidad_requerida * $this->material->precio_estimado;
    }

    /**
     * Calcula el importe de comparativa de este material requerido.
     * 
     * @return float
     */
    public function getImporteComparativaAttribute()
    {
        return $this->cantidad_comparativa * $this->material->precio_proyecto_comparativo;
    }

    /**
     * Obtiene el importe de acuerdo al tipo de cambio de la moneda homologada.
     * 
     * @param  float $tipo_cambio
     * @return float
     */
    public function getImporteEstimado($tipo_cambio)
    {
        if (! $this->material->moneda) {
            return 0;
        }
        
        if ($this->material->moneda->eslocal()) {
            return $this->importe / $tipo_cambio;
        }

        return $this->importe;
    }

    /**
     * Obtiene el precio estimado de acuerdo al tipo de cambio de la moneda homologada.
     * 
     * @param  float $tipo_cambio
     * @return float
     */
    public function getPrecioEstimado($tipo_cambio)
    {
        if (! $this->material->moneda) {
            return 0;
        }
        
        if ($this->material->moneda->eslocal()) {
            return $this->material->precio_estimado / $tipo_cambio;
        }

        return $this->material->precio_estimado;
    }

    /**
     * Obtiene el importe de comparativa de acuerdo al tipo de cambio de la moneda homologada.
     * 
     * @param  float $tipo_cambio
     * @return float
     */
    public function getImporteComparativa($tipo_cambio)
    {
        if (! $this->material->moneda_proyecto_comparativo) {
            return 0;
        }
        
        if ($this->material->moneda_proyecto_comparativo->eslocal()) {
            return $this->importe_comparativa / $tipo_cambio;
        }

        return $this->importe_comparativa;
    }

    /**
     * Obtiene el precio de comparativa de acuerdo al tipo de cambio de la moneda homologada.
     * 
     * @param  float $tipo_cambio
     * @return float
     */
    public function getPrecioComparativa($tipo_cambio)
    {
        if (! $this->material->moneda_proyecto_comparativo) {
            return 0;
        }
        
        if ($this->material->moneda_proyecto_comparativo->eslocal()) {
            return $this->material->precio_proyecto_comparativo / $tipo_cambio;
        }

        return $this->material->precio_proyecto_comparativo;
    }

    /**
     * Tipo de area a la que pertenece este material requerido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    /**
     * Material relacionado con este requerido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo [description]
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    public function material_requerido()
    {
        return $this->belongsTo(MaterialRequerido::class, 'id_material_requerido');
    }
    
    public function MaterialesAsignados()
    {
        $materiales_asignados = ItemAsignacion::whereRaw("id_material =" . $this->id_material . " and id_area_destino = ". $this->id_area)->get();
        return $materiales_asignados;
        
    }
    
    public function cantidadMaterialesAsignados()
    {
        $materiales_asignados = ItemAsignacion::whereRaw("id_material =" . $this->id_material . " and id_area_destino = ". $this->id_area)->sum("cantidad_asignada");
        return (float)$materiales_asignados;
        
    }
    public function itemsAsignacion(){
        return $this->hasMany(ItemAsignacion::class, "id_material_requerido");
    }
    
    public function cantidadMaterialesPendientes()
    {
        $cantidad_materiales_asignados = $this->cantidadMaterialesAsignados();
        $cantidad_materiales_requeridos = $this->cantidad_requerida;
        $cantidad_materiales_pendientes = $cantidad_materiales_requeridos - $cantidad_materiales_asignados;
        return (float)$cantidad_materiales_pendientes;
        
    }
    
    public function desvinculaMaterialRequeridoAreaTipo(){
        $this->material_requerido()->dissociate();
        $this->save();
    }
   
    public function delete() {
        $materiales_asignados = $this->MaterialesAsignados();
        if(count($materiales_asignados)>0){
            $this->desvinculaMaterialRequeridoAreaTipo();
        }else{
            parent::delete();
        }
        
    }
    
    public function cantidadAsignacionesValidadas()
    {
        return DB::connection($this->connection)
            ->table('Equipamiento.asignacion_items')
            ->join('Equipamiento.asignacion_item_validacion', 'Equipamiento.asignacion_items.id','=','Equipamiento.asignacion_item_validacion.id_item_asignacion')
            ->where('Equipamiento.asignacion_items.id_material_requerido', $this->id)
            ->sum('cantidad_asignada');
        
    }
}
