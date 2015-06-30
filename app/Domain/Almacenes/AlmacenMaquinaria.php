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

    /**
     * Categoria del equipo
     *
     * @return mixed
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id');
    }

    /**
     * Propiedad del equipo
     *
     * @return mixed
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'id_propiedad', 'id');
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
