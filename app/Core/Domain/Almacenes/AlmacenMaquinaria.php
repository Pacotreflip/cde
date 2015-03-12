<?php namespace Ghi\Core\Domain\Almacenes;

use Ghi\Maquinaria\Domain\Conciliacion\Models\ItemEntradaEquipo;

class AlmacenMaquinaria extends Almacen {

    /**
     * @var array
     */
    protected $fillable = ['descripcion', 'numero_economico'];

    /**
     * @var string
     */
    protected $presenter = AlmacenPresenter::class;

    /**
     * Categoria del equipo
     *
     * @return mixed
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Propiedad del equipo
     *
     * @return mixed
     */
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'id_propiedad', 'id_propiedad');
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
     * Maquinas relacionadas con este equipo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function maquinas()
    {
        return $this->hasMany(ItemEntradaEquipo::class, 'id_almacen', 'id_almacen');
    }

} 