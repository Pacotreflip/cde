<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Core\Models\Obra;
use Kalnoy\Nestedset\Node;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Illuminate\Support\Facades\DB;
class AreaTipo extends Node
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.areas_tipo';

    protected $fillable = ['nombre', 'descripcion', 'clave'];

    /**
     * Obtiene las areas tipo que son las ultimas en la jerarquia.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyLeafs($query)
    {
        return $query->whereRaw('_rgt - _lft = 1');
    }

    /**
     * Obra relacionada con este tipo de area
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Materiales requeridos para este tipo de area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materialesRequeridos()
    {
        return $this->hasMany(MaterialRequerido::class, 'id_tipo_area')->with('material');
    }

    /**
     * Areas asignadas a este tipo de area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas()
    {
        return $this->hasMany(Area::class, 'tipo_id')->orderBy('_lft');
    }

    /**
     * Areas que estan asignadas a este area tipo dentro de otra area.
     * 
     * @param  Area   $area
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function areasAsignadasDentroDe(Area $area)
    {
        return $this->areas()
            ->where('_lft', '>', $area->_lft)
            ->where('_rgt', '<', $area->_rgt)
            ->get();
    }

    /**
     * Obtiene la ruta de esta area tipo.
     * 
     * @return string
     */
    public function getRutaAttribute()
    {
        return $this->ruta(' / ');
    }

    /**
     * Genera la ruta de esta area tipo.
     * 
     * @param  string $separador
     * @return string
     */
    public function ruta($separador = '/')
    {
        return $this->getAncestors()
            ->push($this)
            ->implode('nombre', $separador);
    }

    /**
     * Numero de materiales requeridos para este tipo de area.
     * 
     * @return int
     */
    public function conteoMateriales()
    {
        return $this->materialesRequeridos->count();
    }

    /**
     * Numero de areas asociadas con este tipo de area.
     * 
     * @return int
     */
    public function conteoAreas()
    {
        return $this->areas->count();
    }

    /**
     * Costo total estimado de este tipo de area.
     * 
     * @return float
     */
    public function costoEstimado()
    {
        return $this->materialesRequeridos->sum('precio_estimado');
    }

    /**
     * Crea un nuevo tipo de area dentro de otro.
     *
     * @param array $data
     * @return self
     */
    public static function nuevo(array $data)
    {
        return new static($data);
    }

    /**
     * Relaciona este tipo de area con una obra.
     * 
     * @param  Obra $obra
     * @return self
     */
    public function enObra(Obra $obra)
    {
        $this->obra()->associate($obra);
        return $this;
    }

    /**
     * Mueve este tipo dentro de otro al final.
     * 
     * @param  AreaTipo|null $parent
     * @return self
     */
    public function dentroDe($parent = null)
    {
        if (! $parent) {
            $this->makeRoot();
            return $this;
        }

        if (! $this->isChildOf($parent)) {
            $this->appendTo($parent);
        }

        return $this;
    }

    /**
     * Agrega materiales requeridos a este tipo de area.
     *
     * @param $id_material
     * @return AreaTipo
     */
    public function agregaArticuloRequerido($id_material)
    {
        $material_requerido = new MaterialRequerido([
            'id_material' => $id_material,
            'cantidad_requerida' => 1,
            'precio_estimado' => 0.0,
            'cantidad_comparativa' => null,
            'precio_comparativa' => null,
            'existe_para_comparativa' => true,
        ]);
        $this->materialesRequeridos()->save($material_requerido);
        return $material_requerido;
    }

    /**
     * Elimina materiales requeridos.
     * 
     * @param array $ids
     */
    public function quitaMaterialesRequeridos(array $ids)
    {
        foreach ($ids as $id_articulo) {
            /*se hace de este modo para validar antes de eliminar si hay artículos recibidos de este tipo */
            $materiales_requeridos_areas = MaterialRequeridoArea::where("id_material_requerido",$id_articulo)->get();
            foreach($materiales_requeridos_areas as $material_requerido_area){
                $material_requerido_area->delete();
            }
            $this->materialesRequeridos->find($id_articulo)->delete();
        }
    }
    
    public function area_padre(){
        return $this->belongsTo(AreaTipo::class, "parent_id");
    }
    
    public function areas_hijas(){
        return $this->hasMany(AreaTipo::class, "parent_id", "id");
    }
    
    public function ids_areas(){
        $ids = [];
        $areas = $this->areas;
        foreach($areas as $area){
            $ids[] = $area->id;
        }
        return $ids;
    }
    public static function arregloArticulosRequeridosXLS($id){
        $resultados = DB::connection("cadeco")->select("

SELECT     materiales.numero_parte
, materiales.descripcion
, materiales.descripcion_larga
, materiales.unidad
, Equipamiento.materiales_requeridos.cantidad_requerida, 
                      materiales.precio_estimado
                      , monedas.nombre AS moneda_nativa
, dbo.ConversionTC(Equipamiento.materiales_requeridos.cantidad_requerida*materiales.precio_estimado,monedas.id_moneda, 2,0,0,0) as importe_estimado_moneda_homologada
                      , Equipamiento.materiales_requeridos.cantidad_comparativa, 
                      dbo.materiales.precio_proyecto_comparativo, monedas_1.nombre AS moneda_nativa_comparativa
, dbo.ConversionTC(Equipamiento.materiales_requeridos.cantidad_comparativa*materiales.precio_proyecto_comparativo,monedas_1.id_moneda, 2,0,0,0) as importe_comparativa_moneda_homologada

FROM         monedas RIGHT OUTER JOIN
                      materiales ON monedas.id_moneda = materiales.id_moneda LEFT OUTER JOIN
                      monedas monedas_1 ON materiales.id_moneda_proyecto_comparativo = monedas_1.id_moneda RIGHT OUTER JOIN
                      Equipamiento.areas_tipo RIGHT OUTER JOIN
                      Equipamiento.materiales_requeridos ON Equipamiento.areas_tipo.id = Equipamiento.materiales_requeridos.id_tipo_area ON 
                      materiales.id_material = Equipamiento.materiales_requeridos.id_material
WHERE     (Equipamiento.areas_tipo.id = {$id})
    order by materiales.descripcion
                ");
        
        return  json_decode(json_encode($resultados), true);
    }
}
