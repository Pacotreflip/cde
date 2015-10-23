<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Inventarios\Transaccion;
use Ghi\Equipamiento\Transacciones\Transaccion as OrdenCompra;

class Recepcion extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.recepciones';

    /**
     * @var array
     */
    protected $dates = ['fecha_recepcion'];

    /**
     * @var array
     */
    protected $fillable = [
        'fecha_recepcion',
        'referencia_documento',
        'orden_embarque',
        'numero_pedido',
        'persona_recibio',
        'observaciones'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->asignaNuevoFolio();

            $transaccion = new Transaccion;
            $transaccion->creada_por = 'ubueno';
            $transaccion->save();
            $transaccion->transaccion()->associate($model);
        });
    }

    public function transaccion()
    {
        return $this->belongsTo(Transaccion::class, 'id');
    }

    /**
     * Obra relacionada con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function obra()
    {
        return $this->belongsTo(Obra::class, 'id_obra', 'id_obra');
    }

    /**
     * Proveedor relacionado con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Proveedor::class, 'id_empresa', 'id_empresa');
    }

    /**
     * Orden de compra asociada con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function compra()
    {
        return $this->belongsTo(OrdenCompra::class, 'id_orden_compra', 'id_transaccion');
    }

    /**
     * Items relacionados con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function items()
    {
        return $this->hasMany(ItemRecepcion::class, 'id_recepcion');
    }

     /**
     * Obtiene el siguiente folio disponible para esta recepcion.
     * 
     * @return integer
     */
    protected function asignaNuevoFolio()
    {
        $this->numero_folio = static::where('id_obra', $this->id_obra)
            ->max('numero_folio') + 1;
    }

    /**
     * Recibe un material en este folio de recepcion.
     * 
     * @param Material $material
     * @param float    $cantidad
     * @param float    $precio
     * 
     * @return void
     */
    public function recibeMaterial(Material $material, Area $area, $cantidad, $precio = 0)
    {
        if ($cantidad <= 0) {
            return;
        }

        $item = new ItemRecepcion;
        $item->id_material = $material->id_material;
        $item->cantidad_recibida = $cantidad;
        $item->precio = $precio;
        $item->unidad = $material->unidad_compra;
        $item->id_area_almacenamiento = $area->id;
        var_dump($this->id);
        $this->items()->save($item);

        $inventario = $material->nuevoInventarioEnArea($area);
        $inventario->incrementaExistencia($cantidad, $this->transaccion);
    }
}
