<?php

namespace Ghi\Equipamiento\Articulos;

use Ghi\Equipamiento\Areas\Tipo;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Material extends Model
{
    const MAX_HIJOS_EN_FAMILIA = 999;

    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'materiales';

    /**
     * @var string
     */
    protected $primaryKey = 'id_material';

    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'descripcion_larga', 'numero_parte', 'codigo_externo', ];

    /**
     * @var bool
     */
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
        return $this->belongsToMany(Tipo::class, 'Equipamiento.requerimientos', 'id_material', 'id_tipo_area')
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
        // por la inconsistencia de que mano de obra y servicios comparten el mismo numero
        // de tipo
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
}
