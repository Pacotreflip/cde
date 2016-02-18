<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Core\Models\Obra;
use Kalnoy\Nestedset\Node;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Inventarios\Inventario;
use Ghi\Equipamiento\Inventarios\Exceptions\InventarioNoEncontradoException;

class Area extends Node
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.areas';

    /**
     * Campos afectables por asignacion masiva
     *
     * @var array
     */
    protected $fillable = ['nombre', 'clave', 'descripcion'];

    /**
     * Obra relacionada con esta area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Area tipo de esta area.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo()
    {
        return $this->belongsTo(AreaTipo::class, 'tipo_id');
    }

    /**
     * Inventarios relacionados con esta area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_area');
    }

    /**
     * Materiales en inventario y con existencia en esta area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'Equipamiento.inventarios', 'id_area', 'id_material')
            ->where('Equipamiento.inventarios.cantidad_existencia', '>', 0)
            ->withPivot('id', 'cantidad_existencia');
    }

    /**
     * Descendientes de esta area que tienen asignado un area tipo.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function descendientesConAreaTipo()
    {
        return $this->descendants()
            ->with('tipo')
            ->whereNotNull('tipo_id')
            ->get()
            ->groupBy('tipo_id');
    }

    /**
     * Mueve esta area dentro de otra area al final.
     *
     * @param Area $parent
     * @return Area
     */
    public function moverA(Area $parent)
    {
        if (! $this->isChildOf($parent)) {
            $this->appendTo($parent);
        }

        return $this;
    }

    /**
     * Asigna el subtipo a esta area.
     *
     * @param AreaTipo $area_tipo
     * @return Area
     */
    public function asignaTipo($area_tipo = null)
    {
        if (! $area_tipo) {
            $this->tipo()->dissociate();
        }else{
            $this->tipo()->associate($area_tipo);
        }

        

        return $this;
    }
    
    /**
     * Agrega materiales requeridos a esta area.
     *
     * @param AreaTipo $area_tipo
     * @return Area
     */
    public function getArticuloRequeridoDesdeAreaTipo(AreaTipo $area_tipo )
    {
        $materiales_requeridos = $area_tipo->materialesRequeridos;
        $materiales_requeridos_area = [];
        foreach($materiales_requeridos as $material_requerido){
            $materiales_requeridos_area [] = new MaterialRequeridoArea([
                'id_material' => $material_requerido->id_material,
                'id_material_requerido' => $material_requerido->id,
                'cantidad_requerida' => $material_requerido->cantidad_requerida,
                'cantidad_comparativa' => $material_requerido->cantidad_comparativa,
                'existe_para_comparativa' => $material_requerido->existe_para_comparativa,
            ]);
        }
        
        return $materiales_requeridos_area;
    }
    
    /**
     * Agrega materiales requeridos a esta area.
     *
     * @param $id_material
     * @return Area
     */
    public function agregaArticuloRequerido($id_material, MaterialRequerido $material_requerido = null, $cantidad_requerida = 1, $cantidad_comparativa = null, $existe_para_comparativa = true)
    {
        if($material_requerido){
            $this->materialesRequeridos()->create([
            'id_material' => $id_material,
            'id_material_requerido' => $id_material_requerido,
            'cantidad_requerida' => $cantidad_requerida,
            'cantidad_comparativa' => $cantidad_comparativa,
            'existe_para_comparativa' => $existe_para_comparativa,
        ]);
        }
        

        return $this;
    }

    /**
     * Obtiene un inventario de un material en esta area.
     * 
     * @param  Material $material
     * @throws InventarioNoEncontradoException
     * @return \Ghi\Equipamiento\Inventarios\Inventario
     */
    public function getInventarioDeMaterial(Material $material)
    {
        $inventario = $this->inventarios()->where('id_material', $material->id_material)->first();

        if (! $inventario) {
            throw new InventarioNoEncontradoException;
        }

        return $inventario;
    }

    /**
     * Genera la cadena que representa la ruta de esta area.
     *
     * @param string $separator
     * @return string
     */
    public function ruta($separator = '/')
    {
        $ruta = '';

        foreach ($this->getAncestors() as $area) {
            $ruta .= $area->nombre.$separator;
        }

        $ruta .= $this->nombre;

        return $ruta;
    }
    
    public function materialesRequeridos(){
        return $this->hasMany(MaterialRequeridoArea::class, "id_area");
    }
}
