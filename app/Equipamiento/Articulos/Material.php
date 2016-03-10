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
        if ($value == TipoMaterial::TIPO_MANO_OBRA and $this->attributes['marca'] == 1) {
            return new TipoMaterial(TipoMaterial::TIPO_SERVICIOS);
        }

        return new TipoMaterial($value);
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
    
    public function cantidad_asignada($id_area){
        return DB::connection($this->connection)
            ->table('Equipamiento.asignacion_items')
            ->where('id_area_destino', $id_area)
            ->where('id_material', $this->id_material)
            ->sum('cantidad_asignada');
    }
    
    public function cantidad_esperada($id_area){
        $area = Area::findOrFail($id_area);
        $esperados = $area->materialesRequeridos()->where('id_material', $this->id_material)->sum('cantidad_requerida');
        return $esperados;
    }
    
    public function items_asignacion(){
        return $this->hasMany(ItemAsignacion::class, "id_material", "id_material");
    }
    public function areas_asignacion(){
        return $this->hasMany("Equipamiento.asignacion_items", "id_material", "id_material")
            ->leftJoin("equipamiento.areas", "");
    }
}
