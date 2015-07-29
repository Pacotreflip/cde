<?php

namespace Ghi\Domain\Almacenes;

use Ghi\Domain\Core\Inventario;
use Ghi\Domain\Core\Material;

class AlmacenMaquinaria extends Almacen
{
    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'numero_economico', 'tipo_almacen'];

    /**
     * @var string
     */
    protected $presenter = AlmacenMaquinariaPresenter::class;

    public function getPropiedadAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }
        return new Propiedad($value);
    }

    public function setPropiedadAttribute($value)
    {
        $this->attributes['propiedad'] = new Propiedad($value);
    }

    public function getClasificacionAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }
        return new Clasificacion($value);
    }

    public function setClasificacionAttribute($value)
    {
        $this->attributes['clasificacion'] = new Clasificacion($value);
    }

    /**
     * Tipo de material de este equipo
     *
     * @return mixed
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material');
    }

    /**
     * Horas mensuales relacionadas con este almacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function horasMensuales()
    {
        return $this->hasMany(HoraMensual::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Equipos que han entrado a este almacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipos()
    {
        return $this->hasMany(Inventario::class, 'id_almacen', 'id_almacen')->whereNotNull('referencia');
    }
}
