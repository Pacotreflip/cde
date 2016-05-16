<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Core\Models\Obra;
use Kalnoy\Nestedset\Node;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Inventarios\Inventario;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Inventarios\Exceptions\InventarioNoEncontradoException;
use Ghi\Equipamiento\Asignaciones\ItemAsignacion;
use Ghi\Equipamiento\Cierres\CierrePartida;
use Ghi\Equipamiento\Areas\Concepto;

class Area extends Node
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.areas';

    /**
     * Campos afectables por asignacion masiva
     *
     * @var array
     */
    protected $fillable = ['nombre', 'clave', 'descripcion', 'id_obra'];

    /**
     * Obra relacionada con esta area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    private $arr_areas_hijas ;
    
    
    private $cantidad_asignada = null;
    private $cantidad_requerida= null ;
    private $cantidad_validada = null;
    private $cantidad_almacenada = null;
    private $cantidad_areas_cerrables = null;
    private $cantidad_areas_cerradas = null;
    private $cantidad_areas_entregadas = null;
    
    
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
    
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'id_concepto', 'id_concepto');
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
     * Materiales asignados a esta area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function materiales_asignados()
    {
        return $this->belongsToMany(Material::class, 'Equipamiento.asignacion_items', 'id_area_destino', 'id_material')
            ->withPivot('cantidad_asignada');
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
            
//            $materiales_requeridos_tipo = $this->tipo->materialesRequeridos;
//            foreach($materiales_requeridos_tipo as $material_requerido_tipo){
//                $material_requerido = MaterialRequeridoArea::whereRaw("id_area = ". $this->id ." and id_material_requerido = ". $material_requerido_tipo->id);
//                //meter despues lo de la validación de artículos asignados
//                $material_requerido->delete();
//            }
            $this->tipo()->dissociate();
        }else{
//            if($this->tipo != $area_tipo && $this->tipo){
//                $materiales_requeridos_tipo = $this->tipo->materialesRequeridos;
//                foreach($materiales_requeridos_tipo as $material_requerido_tipo){
//                    $material_requerido = MaterialRequeridoArea::whereRaw("id_area = ". $this->id ." and id_material_requerido = ". $material_requerido_tipo->id);
//                    //meter despues lo de la validación de artículos asignados
//                    $material_requerido->delete();
//                }
//            }
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
    public function getPosicionRespectoHermanas(){
        $padre = $this->area_padre;
        if($padre){
            $areas_hermanas = $padre->children()->orderBy('_lft',"asc")->get();
        }else{
            $areas_hermanas = Area::whereRaw("parent_id is NULL AND id_obra = {$this->id_obra} ")->orderBy('_lft',"asc")->get();
        }
        $posicion = 0;
        foreach($areas_hermanas as $area_hermana){
            if($this->id == $area_hermana->id){
                break;
            }
            $posicion++;
        }
        return $posicion;
    }
    public function setConcepto(){
        $nivel = $this->calculaNivel();
        $concepto_exitente = Concepto::where("nivel", $nivel)->where("id_obra", $this->id_obra)->first();
        if($concepto_exitente){
            $this->id_concepto = $concepto_exitente->id_concepto;
            $this->save();
        }else{
            $concepto = new Concepto([
                "id_obra"=>$this->id_obra,
                "control_equipamiento"=>1,
                "descripcion"=>$this->nombre
            ]);
            $concepto->nivel = $nivel;
            $concepto->save();
            $this->id_concepto = $concepto->id_concepto;
            $this->save();
        }
        
        return $this->id_concepto;
    }
    public function calculaNivel(){
        #obtenemos todos los ancestros
        $concepto_raiz = Concepto::whereRaw("id_obra = {$this->id_obra} and len(nivel)=4  and control_equipamiento = 1")->first();
        $nivel = $concepto_raiz->nivel;
        foreach ($this->getAncestors() as $area_ancestro) {
            $posicion = $this->zerofill(3,$area_ancestro->getPosicionRespectoHermanas());
            $nivel .= $posicion.".";
            if(!($area_ancestro->concepto)){
                $concepto_exitente = Concepto::where("nivel", $nivel)->where("id_obra", $area_ancestro->id_obra)->first();
                if($concepto_exitente){
                    $area_ancestro->id_concepto = $concepto_exitente->id_concepto;
                    $area_ancestro->save();
                }else{
                    $concepto = new Concepto([
                        "id_obra"=>$area_ancestro->id_obra,
                        "control_equipamiento"=>1,
                        "descripcion"=>$area_ancestro->nombre
                    ]);
                    $concepto->nivel = $nivel;
                    $concepto->save();
                    $area_ancestro->id_concepto = $concepto->id_concepto;
                    $area_ancestro->save();
                }
            }
        }
        $posicion_actual = $this->zerofill(3,$this->getPosicionRespectoHermanas());
        $nivel .= $posicion_actual.".";
        return $nivel;
    }
    private function zerofill( $cantidad, $valor){
    $cad_ceros = "";
    $cantidad = $cantidad - strlen($valor);
    for($i=0; $i<$cantidad; $i++){
        $cad_ceros.='0';
    }
    return $cad_ceros . $valor;
}
    public function soloRuta($separator = '/')
    {
        $ruta = '';

        foreach ($this->getAncestors() as $area) {
            $ruta .= $area->nombre.$separator;
        }


        return $ruta;
    }
    public function materialesRequeridos(){
        return $this->hasMany(MaterialRequeridoArea::class, "id_area");
    }
    
    public function materialesAsignados(){
        return $this->hasMany(ItemAsignacion::class, "id_area_destino");
    }
    private function getHijas(Area $area){
        if(!isset($this->arr_areas_hijas[$this->id]["terminado"])){
            $this->arr_areas_hijas[$this->id]["areas"][$this->id] = $this;
            $areas_hijas = $area->areas_hijas;
            if($areas_hijas){
                foreach($areas_hijas as $area_hija){
                    $this->arr_areas_hijas[$this->id]["areas"][$area_hija->id] = $area_hija;
                    $this->getHijas($area_hija);
                }
            }
            
        }
    }
    public function getIdsDescendientes(){
        //$this->arr_areas_hijas = [];
        $this->getHijas($this);
        $this->arr_areas_hijas[$this->id]["terminado"] = 1;
        $id = [];
        foreach($this->arr_areas_hijas[$this->id]["areas"] as $area_hija){
            $id[] = $area_hija->id;
        }
        return $id;
    }
    public function cantidad_asignada($id_material = ""){
        if($this->cantidad_asignada == null){
            $ids_area = $this->getIdsDescendientes();
            $ciclos = ceil(count($ids_area)/2000);
            $this->cantidad_asignada = 0;
            for($i = 0; $i<=$ciclos; $i++){
                $ids = array_slice($ids_area, $i*2000, 2000);
                if($id_material > 0){
                    $this->cantidad_asignada += DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->where('id_material', $id_material)
                    ->whereIn('id_area_destino', $ids)
                    ->sum('cantidad_asignada');
                }else{
                    $this->cantidad_asignada += DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->whereIn('id_area_destino', $ids)
                    ->sum('cantidad_asignada');
                }
            }
        }
        return $this->cantidad_asignada;
    }
    public function cantidad_requerida($id_material = ""){
        if($this->cantidad_requerida == null){
            $ids_area = $this->getIdsDescendientes();
            $ciclos = ceil(count($ids_area)/2000);
            $this->cantidad_requerida = 0;

            for($i = 0; $i<=$ciclos; $i++){
                $ids = array_slice($ids_area, $i*2000, 2000);
                if($id_material > 0){
                    $this->cantidad_requerida += DB::connection($this->connection)
                    ->table('Equipamiento.materiales_requeridos_area')
                    ->where('id_material', $id_material)
                    ->whereIn('id_area', $ids)
                    ->sum('cantidad_requerida');
                }else{
                    $this->cantidad_requerida += DB::connection($this->connection)
                    ->table('Equipamiento.materiales_requeridos_area')
                    ->whereIn('id_area', $ids)
                    ->sum('cantidad_requerida');
                }
            }
        }
        return $this->cantidad_requerida;
    }
    public function cantidad_validada($id_material = ""){
        if($this->cantidad_validada == null){
            $ids_area = $this->getIdsDescendientes();
            $ciclos = ceil(count($ids_area)/2000);
            $this->cantidad_validada = 0;

            for($i = 0; $i<=$ciclos; $i++){
                $ids = array_slice($ids_area, $i*2000, 2000);
                if($id_material > 0){
                    $this->cantidad_validada += DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->join('Equipamiento.asignacion_item_validacion', 'Equipamiento.asignacion_items.id','=','Equipamiento.asignacion_item_validacion.id_item_asignacion')
                    ->where('id_material', $id_material)
                    ->whereIn('id_area_destino', $ids)
                    ->sum('cantidad_asignada');
                }else{
                    $this->cantidad_validada += DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->join('Equipamiento.asignacion_item_validacion', 'Equipamiento.asignacion_items.id','=','Equipamiento.asignacion_item_validacion.id_item_asignacion')
                    ->whereIn('id_area_destino', $ids)
                    ->sum('cantidad_asignada');
                }
            }
        }
        
        return $this->cantidad_validada;
    }
    
    public function cantidad_almacenada(){
        if($this->cantidad_almacenada == null){
            $this->cantidad_almacenada = DB::connection($this->connection)
                ->table('Equipamiento.inventarios')
                ->where('id_area', $this->id)
                ->sum('cantidad_existencia');
        }
        return $this->cantidad_almacenada;
    }
    
    public function area_padre(){
        return $this->belongsTo(Area::class, "parent_id");
    }
    
    
    public function areas_hijas(){
        return $this->hasMany(Area::class, "parent_id", "id");
    }
    public function esCerrable(){
        $cerrable = 1;
        $materiales_requeridos = $this->materialesRequeridos->sum("cantidad_requerida");
        
        //dd($materiales_requeridos, $materiales_asignados);
        if($materiales_requeridos > 0){
            $materiales_asignados = $this->materialesAsignados->sum("cantidad_asignada");
            if(abs($materiales_requeridos-$materiales_asignados)>0.1){
                $cerrable = 0;
            }
        }else{
            $cerrable = 0;
        }
        return $cerrable;
    }
    
    public function esEntregable(){
        $entregable = 0;
        if(count($this->cierre_partida)>0){
            $entregable = 1;
        }
        return $entregable;
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

    public function cierre_partida(){
        return $this->hasOne(CierrePartida::class, "id_area");
    }
    
    public function porcentaje_asignacion(){
        return ($this->cantidad_asignada() / $this->cantidad_requerida()) * 100;
    }
    
    public function porcentaje_validacion(){
        return ($this->cantidad_validada() / $this->cantidad_asignada()) * 100;
    }
    
    public function documentos(){
        return $this->hasMany(AreaDocumento::class, "id_area");
    }
    
    public function cantidad_areas_cerrables(){
        
        if($this->cantidad_areas_cerrables == null){
            $this->getHijas($this);
            $this->arr_areas_hijas[$this->id]["terminado"] = 1;
            $id = [];
            foreach($this->arr_areas_hijas[$this->id]["areas"] as $area_hija){
                if($area_hija->esCerrable() == 1){
                    $id[] = $area_hija->id;
                }
            }
            $this->cantidad_areas_cerrables = count($id);
        }
        return $this->cantidad_areas_cerrables;
    }
    
    public function cantidad_areas_cerradas(){
        if($this->cantidad_areas_cerradas == null){
            $this->getHijas($this);
            $this->arr_areas_hijas[$this->id]["terminado"] = 1;
            $id = [];
            foreach($this->arr_areas_hijas[$this->id]["areas"] as $area_hija){
                if($area_hija->cierre_partida){
                    $id[] = $area_hija->id;
                }
            }
            $this->cantidad_areas_cerradas = count($id);
        }
        return $this->cantidad_areas_cerradas;
    }
    
    public function cantidad_areas_entregadas(){
        if($this->cantidad_areas_entregadas == null){
            $this->getHijas($this);
            $this->arr_areas_hijas[$this->id]["terminado"] = 1;
            $id = [];
            foreach($this->arr_areas_hijas[$this->id]["areas"] as $area_hija){
                if($area_hija->entrega_partida()){
                    $id[] = $area_hija->id;
                }
            }
            $this->cantidad_areas_entregadas = count($id);
        }
        return $this->cantidad_areas_entregadas;
    }
    public function entrega_partida(){
        if($this->cierre_partida){
            return $this->cierre_partida->entrega_partida;
        }else{
            return false;
        }
    }
    public function porcentaje_cierre(){
        
        return ($this->cantidad_areas_cerradas() / $this->cantidad_areas_cerrables()) * 100;
    }
    
    public function porcentaje_entrega(){
        
        return ($this->cantidad_areas_entregadas() / $this->cantidad_areas_cerradas()) * 100;
    }
    
    public function acumulador(){
        return $this->hasOne(AreaAcumuladores::class, "id_area", "id");
    }
}
