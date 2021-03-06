<?php

namespace Ghi\Equipamiento\Articulos;

use Ghi\Equipamiento\Areas\Area;
use Ghi\Equipamiento\Areas\AreaTipo;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Inventarios\Inventario;
use Ghi\Equipamiento\Transacciones\ItemTransaccion;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ghi\Equipamiento\Inventarios\Exceptions\InventarioNoEncontradoException;
use Illuminate\Support\Facades\DB;
use Ghi\Equipamiento\Moneda;
use Ghi\Equipamiento\Asignaciones\ItemAsignacion;
use Ghi\Equipamiento\Areas\Areas;
use Illuminate\Database\Eloquent\Collection;
use Ghi\Equipamiento\Areas\MaterialRequeridoArea;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Recepciones\ItemRecepcion;
use Ghi\Equipamiento\ReporteCostos\MaterialSecrets;
use Ghi\Equipamiento\ReporteCostos\MaterialSecretsMaterialDreams;
use Ghi\Equipamiento\ReporteCostos\AreaDreamsMateriales;
use Ghi\Equipamiento\Transacciones\Item;
use Carbon\Carbon;
class Material extends Model
{
    const MAX_HIJOS_EN_FAMILIA = 999;

    protected $connection = 'cadeco';

    protected $table = 'materiales';

    protected $primaryKey = 'id_material';

    protected $fillable = ['descripcion', 'descripcion_larga', 'numero_parte', 'codigo_externo', ];

    protected $casts = [
        'id_material' => 'int',
    ];

    public $timestamps = false;

    /**
     * Directorio base de almacenamiento de la ficha tecnica
     * 
     * @var string
     */
    protected $directorioBase = 'articulo/fichas';

    /**
     * Scope para obtener los materiales sin familias
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSoloMateriales($query)
    {
        return $query->whereRaw('LEN(nivel) > 4');
    }
    /**
     * Clasificador al que pertenece este articulo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clasificador()
    {
        return $this->belongsTo(Clasificador::class, 'id_clasificador');
    }

    /**
     * Fotos que tiene este articulo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fotos()
    {
        return $this->hasMany(Foto::class, 'id_material', 'id_material');
    }

    /**
     * Tipos de area donde se requiere este material
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tiposArea()
    {
        return $this->belongsToMany(AreaTipo::class, 'Equipamiento.requerimientos', 'id_material', 'id_tipo_area')
            ->withTimestamps();
    }

    /**
     * Obtiene la familia de este material
     *
     * @return Material|null
     */
    public function familia()
    {
        return static::where('tipo_material', $this->tipo_material)
            ->where('nivel', substr($this->nivel, 0, 4))
            ->first();
    }

    /**
     * Items de recepciones de este material.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(ItemTransaccion::class, 'id_material', 'id_material');
    }

    /**
     * Al asignar un numero de parte tambien se asigna al codigo externo
     *
     * @param strin $value
     */
    public function setNumeroParteAttribute($value)
    {
        $this->attributes['numero_parte'] = $value;
        $this->attributes['codigo_externo'] = $value;
    }

    /**
     * Convierte el valor de tipo de material
     *
     * @return TipoMaerial
     */
    public function getTipoMaterialAttribute($value)
    {
        // Para diferenciar cuando el material es de tipo servicios y devolver el tipo correcto
        // por la inconsistencia de que mano de obra y servicios comparten el mismo numero de tipo
        if(array_key_exists("marca", $this->attributes)){
            if ($value == TipoMaterial::TIPO_MANO_OBRA and $this->attributes['marca'] == 1) {
                return new TipoMaterial(TipoMaterial::TIPO_SERVICIOS);
            }
        }
        return new TipoMaterial($value);
    }
    
    public function getIndiceRecepcionAttribute($id_obra){
        $compras =  Transaccion::ordenesCompraMateriales()
            ->join('items','items.id_transaccion','=','transacciones.id_transaccion')
            ->join('materiales','items.id_material','=','materiales.id_material')
            ->where('id_obra', $id_obra)
            ->where('materiales.id_material', $this->id_material)
            ->where('transacciones.id_transaccion', $this->id_oc)
            ->groupBy(['transacciones.id_transaccion','transacciones.numero_folio'
                ,'transacciones.fecha','transacciones.id_empresa','transacciones.observaciones'])
            ->orderBy('numero_folio', 'DESC')
            ->select(DB::raw('transacciones.id_transaccion,numero_folio,sum(items.cantidad) as cantidad_requerida'))->get() ; 
        $recibido = 0;
        $requerido = 0;
        foreach($compras as $compra){
            $recibido += $compra->getCantidadRecibidaMaterial($this->id_material);
            $requerido += $compra->cantidad_requerida;
        }
        if($requerido>0){
            return number_format(($recibido/$requerido*100),0,".","");
        }else{
            return "";
        }
    }
    
    public function getCantidadCompradaAttribute(){
        $compras =  Transaccion::ordenesCompraMateriales()
            ->join('items','items.id_transaccion','=','transacciones.id_transaccion')
            ->join('materiales','items.id_material','=','materiales.id_material')
            ->where('materiales.id_material', $this->id_material)
            ->where('transacciones.id_transaccion', $this->id_oc)
            ->groupBy(['transacciones.id_transaccion','transacciones.numero_folio'
                ,'transacciones.fecha','transacciones.id_empresa','transacciones.observaciones'])
            ->orderBy('numero_folio', 'DESC')
            ->select(DB::raw('transacciones.id_transaccion,numero_folio,sum(items.cantidad) as cantidad_requerida'))->get() ; 
        $requerido = 0;
        foreach($compras as $compra){
            $requerido += $compra->cantidad_requerida;
        }
        return number_format($requerido,0,".","");
    }
    
    public function getCantidadRecibidaAttribute(){
        $compras =  Transaccion::ordenesCompraMateriales()
            ->join('items','items.id_transaccion','=','transacciones.id_transaccion')
            ->join('materiales','items.id_material','=','materiales.id_material')
            ->where('materiales.id_material', $this->id_material)
            ->where('transacciones.equipamiento', "1")
            //->where('transacciones.id_transaccion', $this->id_oc)
            ->groupBy(['transacciones.id_transaccion','transacciones.numero_folio'
                ,'transacciones.fecha','transacciones.id_empresa','transacciones.observaciones'])
            ->orderBy('numero_folio', 'DESC')
            ->select(DB::raw('transacciones.id_transaccion,numero_folio,sum(items.cantidad) as cantidad_requerida'))->get() ; 
        $recibido = 0;
        foreach($compras as $compra){
            $recibido += $compra->getCantidadRecibidaMaterial($this->id_material);
        }
        return number_format($recibido,0,".","");
    }
    
    public function getCantidadRecibidaModuloEquipamientoAttribute(){
        $recibido = $this->items_recepcion()->sum("cantidad_recibida");
        return number_format($recibido,0,".","");
    }
    
    public function anio_mes_dia_suministro($folio_oc){
        $dias = DB::connection("cadeco")->select(" select 
            dbo.zerofill(4,transacciones.numero_folio) as folio_oc,
            transacciones.id_transaccion as id_oc,
        fecha_entrega,
        year( fecha_entrega) as anio,
        month( fecha_entrega) as mes,
        day( fecha_entrega) as dia,
          convert(varchar(4),year( fecha_entrega)) + 
        case when len(month( fecha_entrega))=1 then '0' +convert(varchar(4),month( fecha_entrega))
        else convert(varchar(4),month( fecha_entrega)) end +
        case when len(day( fecha_entrega))=1 then '0' +convert(varchar(4),day( fecha_entrega))
        else convert(varchar(4),day( fecha_entrega)) end
          anio_mes_dia, cantidad_programada, items.id_transaccion
         from [Equipamiento].[entregas_programadas] join items
         on(items.id_item = entregas_programadas.id_item )
         join transacciones on(transacciones.id_transaccion = items.id_transaccion)
        where items.id_material = {$this->id_material} and transacciones.numero_folio = {$folio_oc};");
    $dias_arr = [];
    $cantidad_recibida = $this->cantidad_recibida_modulo_equipamiento;
    foreach($dias as $dia){
        $date = Carbon::createFromFormat('Y-m-d', $dia->fecha_entrega);
        $dias_arr[$dia->anio_mes_dia]["fecha"] = $dia->anio_mes_dia;
        $dias_arr[$dia->anio_mes_dia]["folio_oc"] = $dia->folio_oc;
        $dias_arr[$dia->anio_mes_dia]["id_oc"] = $dia->id_oc;
        $dias_arr[$dia->anio_mes_dia]["fecha_entrega"] = $date->format("d-m-Y");
        $dias_arr[$dia->anio_mes_dia]["cantidad"] = $dia->cantidad_programada;

        if($cantidad_recibida>= $dia->cantidad_programada){
            $dias_arr[$dia->anio_mes_dia]["cantidad_recibida"] = $dia->cantidad_programada;
            $cantidad_recibida -= $dia->cantidad_programada;
        }else{
            $dias_arr[$dia->anio_mes_dia]["cantidad_recibida"] = $cantidad_recibida;
            $cantidad_recibida = 0;
        }
        if($dia->cantidad_programada > 0){
            $dias_arr[$dia->anio_mes_dia]["indice_suministro"] = number_format(($dias_arr[$dia->anio_mes_dia]["cantidad_recibida"]/$dia->cantidad_programada*100),0,".","");
        }else{
            $dias_arr[$dia->anio_mes_dia]["indice_suministro"] = "";
        }
    }
    
    return $dias_arr;
    }

    /**
     * Asigna el tipo de material a este material
     *
     * @param TipoMaterial $value
     */
    public function setTipoMaterialAttribute($value)
    {
        if (! $value instanceof TipoMaterial) {
            $value = new TipoMaterial($value);
        }

        $this->attributes['tipo_material'] = $value->getTipoReal();
    }

    /**
     * Agrega este material en una familia
     *
     * @param Familia $familia
     * @return Material
     */
    public function agregarEnFamilia(Familia $familia)
    {
        $familia->agregaMaterial($this);
        return $this;
    }

    /**
     * Indica si este material es un hijo de una familia
     *
     * @param Familia $familia
     * @return bool
     */
    public function isChildrenOf(Familia $familia)
    {
        return $this->tipo_material == $familia->tipo_material and
                $this->nivelFamilia() == $familia->nivel;

        return false;
    }

    /**
     * Obtiene el nivel de la familia asociada con este material
     * 
     * @return string
     */
    public function nivelFamilia()
    {
        return substr($this->nivel, 0, 4);
    }

    /**
     * Agrega una foto a este articulo
     *
     * @param Foto $foto
     * @return Articulo
     */
    public function agregaFoto(Foto $foto)
    {
        return $this->fotos()->save($foto);
    }

    /**
     * Asocia este articulo con un clasificador
     *
     * @param Clasificador|null $clasificador
     * @return self
     */
    public function asignaClasificador($clasificador = null)
    {
        if ($clasificador instanceof Clasificador) {
            $this->clasificador()->associate($clasificador);
        } else {
            $this->id_clasificador = null;
        }

        return $this;
    }

    /**
     * Asocia este articulo con una unidad
     *
     * @param Unidad $unidad
     * @return Articulo
     */
    public function asignaUnidad(Unidad $unidad)
    {
        $this->unidad = $unidad->unidad;
        
        if ((string) $this->tipo_material == TipoMaterial::TIPO_MAQUINARIA) {
            $this->unidad_capacidad = $this->unidad;
            $this->unidad_compra = null;
        } else {
            $this->unidad_compra = $this->unidad;
        }

        return $this;
    }

    /**
     * Almacena la ficha tecnica a este articulo
     *
     * @param UploadedFile $file
     */
    public function agregaFichaTecnica(UploadedFile $file)
    {
        $nombre = sha1(time() . '-' . $file->getClientOriginalName());
        $extension = $file->getClientOriginalExtension();
        $this->ficha_tecnica_nombre = "{$nombre}.{$extension}";
        $this->ficha_tecnica_path = sprintf("%s/%s", $this->directorioBase, $this->ficha_tecnica_nombre);
        $file->move($this->directorioBase, $this->ficha_tecnica_nombre);
    }
    
    public function actualizaPreciosEquipamiento($id_material, $precio_estimado, $moneda, $precio_proyecto_comparativo, $moneda_proyecto_comparativa){
        DB::connection("cadeco")->update("update Equipamiento.materiales_requeridos set precio_estimado = $precio_estimado, "
                . "id_moneda = $moneda,"
                . "precio_comparativa = $precio_proyecto_comparativo, "
                . "id_moneda_comparativa = $moneda_proyecto_comparativa"
                . " where id_material = $id_material");
    }

    /**
     * Inventarios de este material.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_material', 'id_material');
    }
    
    public function getTotalEsperado($id_obra){
        return DB::connection($this->connection)
            ->table('dbo.items')
            ->join('dbo.transacciones',"dbo.items.id_transaccion","=", "dbo.transacciones.id_transaccion")
            ->where('dbo.transacciones.tipo_transaccion', "19")
            ->where('dbo.items.id_material', $this->id_material)
            ->where('dbo.transacciones.id_obra', $id_obra)
            ->sum('dbo.items.cantidad');
    }

    /**
     * Obtiene la cantidad de existencias totales de este material.
     * 
     * @return float
     */
    public function getTotalExistencias()
    {
        return $this->inventarios->sum('cantidad_existencia');
    }
    
    /**
     * Obtiene la cantidad de existencias totales de este material.
     * 
     * @return float
     */
    public function getTotalRecibido()
    {
        return $this->items_recepcion->sum('cantidad_recibida');
    }

    /**
     * Obtiene la existencia de este material en un area.
     * 
     * @param  Area  $area
     * @return float
     */
    public function getExistenciaEnArea(Area $area)
    {
        return $this->inventarios()->where('id_area', $area->id)->sum('cantidad_existencia');
    }

    /**
     * Indica si este material tiene existencias en el area indicada.
     * 
     * @param  Area $area
     * @return bool
     */
    public function tieneExistenciasEnArea(Area $area)
    {
        return $this->getExistenciaEnArea($area) > 0 ? true : false;
    }

    /**
     * Indica si hay existencias de este material.
     * 
     * @return bool
     */
    public function tieneExistencias()
    {
        return $this->getTotalExistencias() > 0 ? true : false;
    }

    /**
     * Obtiene el inventario de este articulo en un area.
     *
     * @param  Area $area
     * @return Inventario
     * @throws InventarioNoEncontradoException
     */
    public function getInventarioDeArea(Area $area)
    {
        $inventario = $this->inventarios()->where('id_area', $area->getKey())->first();

        if ($inventario) {
            return $inventario;
        }

        throw new InventarioNoEncontradoException;
    }
    /**
     * Devuelve inventario del material en el área destino,
     * si no éxiste inventario en área destino lo crea 
     * 
     * @param Area $area
     * @return Inventario
     * @throws InventarioNoEncontradoException
     */
    
    public function getInventarioDeAreaDestino(Area $area)
    {
        $inventario = $this->inventarios()->where('id_area', $area->getKey())->first();

        if (!$inventario) {
            $inventario = $this->nuevoInventarioEnArea($area);
        }
        if ($inventario) {
            return $inventario;
        }
        throw new InventarioNoEncontradoException;
    }

    /**
     * Crea una instancia de inventario de este material en el area indicada.
     * 
     * @param  Area $area
     * @throws InventarioNoEncontradoException
     * @return Inventario
     */
    protected function nuevoInventarioEnArea(Area $area, $cantidad = 0)
    {
        $inventario = $this->inventarios()->getRelated()->newInstance();
        $inventario->id_obra = $area->id_obra;
        $inventario->id_area = $area->getKey();
        $inventario->id_material = $this->getKey();
        $inventario->cantidad_existencia = $cantidad;
        $inventario->save();
        return $inventario;
    }

    /**
     * Crea un inventario de este material en el area indicada.
     * 
     * @param  Area  $area
     * @param  float $cantidad
     * @throws InventarioNoEncontradoException
     * @return Inventario
     */
    public function creaInventarioEnArea(Area $area, $cantidad = 0)
    {
        try {
            return $this->getInventarioDeArea($area);
        } catch (InventarioNoEncontradoException $e) {
            $inventario = $this->nuevoInventarioEnArea($area, $cantidad);
            $inventario->save();

            return $inventario;
        }
    }

    /**
     * Incrementa el inventario de este material en un area.
     * 
     * @param  Area  $area
     * @param  float $cantidad
     * @return self
     */
    public function incrementaInventarioEnArea(Area $area, $cantidad)
    {
        $this->getInventarioDeArea($area)->incrementaExistencia($cantidad);

        return $this;
    }

    /**
     * Transfiere existencia de este material a otra area.
     * 
     * @param  float $cantidad
     * @param  Area $origen
     * @param  Area $destino
     * @param  ItemTransaccion $item
     * @return void
     */
    public function transfiereExistencia($cantidad, Area $origen, Area $destino)
    {
        $inventario_origen = $this->getInventarioDeArea($origen);
        $inventario_destino = $this->getInventarioDeAreaDestino($destino);
        $inventario_origen->transferirA($inventario_destino, $cantidad);
    }
    
    public function moneda()
    {
        return $this->hasOne(Moneda::class, 'id_moneda', 'id_moneda');
    }
    
    public function moneda_proyecto_comparativo()
    {
        return $this->hasOne(Moneda::class, "id_moneda", "id_moneda_proyecto_comparativo");
    }
    public function ubicacion_para_entrega($id_area = null){
        if(is_array($id_area)){
            $areas = [];
            $ids_area = $id_area;
            $ciclos = ceil(count($ids_area)/2000);
            for($i = 0; $i<=$ciclos; $i++){
                $ids = array_slice($ids_area, $i*2000, 2000);
                $resultado =  DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->join('Equipamiento.areas',"Equipamiento.asignacion_items.id_area_destino", "=", "Equipamiento.areas.id")
                    ->where('id_material', $this->id_material)
                    ->whereIn('id_area_destino', $ids)
                    ->get();
                foreach ($resultado as $res){
                    $areas[] = $res->id_area_destino;
                }
                
                $resultado2 =  DB::connection($this->connection)
                    ->table('Equipamiento.inventarios')
                    ->where('id_material', $this->id_material)
                    ->whereIn('id_area', $ids)
                    ->get();
                
                foreach ($resultado2 as $res2){
                    $areas[] = $res2->id_area;
                }
               
//                $resultado =  DB::connection($this->connection)
//                    ->table('Equipamiento.areas')
//                    ->whereIn('id', $ids)
//                    ->get();
                
                
            }
            $areas_unique = array_unique($areas);
            $areas_ruta = [];
            foreach($areas_unique as $id_area){
                $areas_ruta[] = Area::findOrFail($id_area)->ruta();
            }
            //dd($areas_ruta);
            return implode(" , ", $areas_ruta);
        }
    }
    public function cantidad_asignada($id_area = null){
        $cantidad = 0;
        if(is_array($id_area)){
            $ids_area = $id_area;
            $ciclos = ceil(count($ids_area)/2000);
            for($i = 0; $i<=$ciclos; $i++){
                $ids = array_slice($ids_area, $i*2000, 2000);
                $cantidad += DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->where('id_material', $this->id_material)
                    ->whereIn('id_area_destino', $ids)
                    ->sum('cantidad_asignada');
            }
        }else{
            if($id_area > 0){
                $cantidad = DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->where('id_area_destino', $id_area)
                    ->where('id_material', $this->id_material)
                    ->sum('cantidad_asignada');
            }else{
                $cantidad = DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->where('id_material', $this->id_material)
                    ->sum('cantidad_asignada');
            }
        }
        return $cantidad;
    }
    
    public function cantidad_cierre($id_area = null){
        $cantidad = 0;
        if(is_array($id_area)){
            $ids_area = $id_area;
            $ciclos = ceil(count($ids_area)/2000);
            for($i = 0; $i<=$ciclos; $i++){
                
                $ids = array_slice($ids_area, $i*2000, 2000);
                $cantidad += DB::connection($this->connection)
                    ->table('Equipamiento.asignacion_items')
                    ->where('id_material', $this->id_material)
                    ->whereIn('id_area_destino', $ids)
                    ->sum('cantidad_asignada');
                $cantidad += DB::connection($this->connection)
                    ->table('Equipamiento.inventarios')
                    ->whereIn('id_area', $id_area)
                    ->where('id_material', $this->id_material)
                    ->sum('cantidad_existencia');
            }
        }else{
            if($id_area > 0){
                
                $cantidad = DB::connection($this->connection)
                ->table('Equipamiento.inventarios')
                ->where('id_area', $id_area)
                ->where('id_material', $this->id_material)
                ->sum('cantidad_existencia');

                $cantidad += DB::connection($this->connection)
                ->table('Equipamiento.asignacion_items')
                ->where('id_area_destino', $id_area)
                ->where('id_material', $this->id_material)
                ->sum('cantidad_asignada');
                
            }
        }
        return $cantidad;
    }
    
    public function cantidad_esperada($id_area = null){
        if($id_area > 0){
            $area = Area::findOrFail($id_area);
            $esperados = $area->materialesRequeridos()->where('id_material', $this->id_material)->sum('cantidad_requerida');
            return $esperados;
        }else{
            $esperados = $this->material_requerido_area->sum('cantidad_requerida');
            return $esperados;
        }
        
    }
    
    public function items_asignacion(){
        return $this->hasMany(ItemAsignacion::class, "id_material", "id_material");
    }
    public function items_recepcion(){
        return $this->hasMany(ItemRecepcion::class, "id_material", "id_material");
    }
    public function areas_asignacion(){
        $items_asignacion = $this->items_asignacion;
        $areas = [];
        foreach($items_asignacion as $item_asignacion){
            $areas[] = $item_asignacion->area_destino;
        }
        $areas_unique = array_unique($areas);
        $areas_collection = new Collection($areas_unique);
        return $areas_collection;
    }
    public function areas_almacenacion(){
        $inventarios = $this->inventarios;
        $areas = [];
        foreach($inventarios as $inventario){
            if($inventario->cantidad_existencia>0){
                $areas[] = $inventario->area;
            }
        }
        $areas_unique = array_unique($areas);
        $areas_collection = new Collection($areas_unique);
        return $areas_collection;
    }
    public function areas_requerido(){
        $materiales_requeridos_area = $this->material_requerido_area;
        $areas = [];
        foreach($materiales_requeridos_area as $material_requerido_area){
            $areas[] = $material_requerido_area->area;
        }
        $areas_unique = array_unique($areas);
        $areas_collection = new Collection($areas_unique);
        return $areas_collection;
    }
    public function material_requerido_area(){
        return $this->hasMany(MaterialRequeridoArea::class, "id_material", "id_material");
    }
    
    public function porcentaje_suministro($id_obra){
        return ($this->getTotalRecibido() / $this->getTotalEsperado($id_obra)) * 100;
    }
    
    public function porcentaje_asignacion(){
        return ($this->cantidad_asignada() / $this->cantidad_esperada()) * 100;
    }
    
    public function ubicacion_entrega(){
        return "d";
    }
    
    /**
     * Scope para obtener los materiales relacionados con equipamiento
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMaterialesEquipamiento($query)
    {
        return $query->leftJoin("items","items.id_material","=", "materiales.id_material")
            ->leftJoin("Equipamiento.materiales_requeridos_area","materiales.id_material","=", "Equipamiento.materiales_requeridos_area.id_material")
            ->leftJoin("transacciones","items.id_transaccion","=", "transacciones.id_transaccion")
            ->whereRaw('LEN(nivel) > 4 and (Equipamiento.materiales_requeridos_area.id_material is not null or materiales.control_equipamiento = 1 or (transacciones.equipamiento = 1 and transacciones.estado != -2))')
            ;
    }
    
    public function ms_md()
    {
        return $this->hasOne(MaterialSecretsMaterialDreams::class, 'id_material_dreams', 'id_material');
    }
    
    public function area_dreams_material()
    {
        return $this->hasOne(AreaDreamsMateriales::class, 'id_material', "id_material");
    }
    public function getIdMaterialSecretsAttribute(){
        if($this->ms_md)
            return $this->ms_md->id_material_secrets;
        else
            return null;
    }
    
    public function getIdAreaReporteAttribute(){
        if($this->area_dreams_material)
            return $this->area_dreams_material->id_area_dreams;
        else
            return null;
    }
    
    public function asignaAreaReporte($id_area_reporte = null){
        if($this->area_dreams_material){
            if($id_area_reporte>0){
                if($id_area_reporte != $this->area_dreams_material->id_area_dreams){
                    $this->area_dreams_material->id_area_dreams = $id_area_reporte;
                    $this->area_dreams_material->save();
                }
            }else{
                //$this->area_dreams_material()->delete();
            }
        }else{
            if($id_area_reporte>0){
                $area_dreams_materiales = new AreaDreamsMateriales();
                $area_dreams_materiales->id_area_dreams = $id_area_reporte;
                $area_dreams_materiales->id_material = $this->id_material;
                $area_dreams_materiales->save();
                
            }
        }
        
    }
    public function asignaMaterialesSecrets($id_material_secrets = null){
        if($this->ms_md){
            if($id_material_secrets>0){
                if($id_material_secrets != $this->ms_md->id_material_secrets){
                    $this->ms_md->id_material_secrets = $id_material_secrets;
                    $this->ms_md->save();
                }
            }else{
                $this->ms_md->delete();
            }
        }else{
            if($id_material_secrets>0){
                $ms_md = new MaterialSecretsMaterialDreams();
                $ms_md->id_material_secrets = $id_material_secrets;
                $ms_md->id_material_dreams = $this->id_material;
                $ms_md->save();
                
            }
        }
        
    }
    public function item_oc(){
        return $this->hasMany(Item::class,"id_material","id_material");
    }
    public function getFechasProgramadasAttribute(){
        $items_oc = $this->item_oc;
        return "0".count($items_oc);
    }
}
