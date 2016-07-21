<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Transacciones\TransaccionTrait;
use Ghi\Equipamiento\Transacciones\Transaccion as OrdenCompra;
use Ghi\Equipamiento\Transacciones\Transaccion;
use Ghi\Equipamiento\Autenticacion\User;
use Ghi\Equipamiento\Asignaciones\Asignacion;
use Ghi\Equipamiento\Comprobantes\Comprobante;

class Recepcion extends Model
{
    use TransaccionTrait;

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
        'numero_remision_factura',
        'orden_embarque',
        'numero_pedimento',
        'persona_recibio',
        'id_usuario',
        'observaciones',
    ];

    /**
     * [boot description].
     * 
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->asignaFolio();
        });
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
     * Recibe un material en este folio de recepcion.
     *
     * @param Material $material
     * @param float $cantidad
     * @param $id_item
     * @param Area $area
     */
    public function agregaMaterial(Material $material, $cantidad, $id_item, $area = null)
    {
        if ($cantidad <= 0) {
            return;
        }

        $item = new ItemRecepcion;
        $item->id_material = $material->id_material;
        $item->cantidad_recibida = $cantidad;
        $item->unidad = $material->unidad_compra;
        $item->id_item = $id_item;

        if ($area) {
            $item->id_area_almacenamiento = $area->id;
            $inventario = $material->creaInventarioEnArea($area);
            $inventario->incrementaExistencia($cantidad, $this->transaccion);
        }

        $this->items()->save($item);
    }
    
    public function usuario_registro(){
        return $this->hasOne(User::class,"idusuario", "id_usuario");
    }
    
    public function asignacion(){
        return $this->hasOne(Asignacion::class,"id_recepcion");
    }
    
    public function comprobantes() {
        return $this->hasMany(Comprobante::class, 'id_recepcion', 'id');
    } 
    
    public function agregaComprobante(Comprobante $comprobante) {
        return $this->comprobantes()->save($comprobante);
    }
    
    public function transacciones(){
        return $this->belongsToMany(Transaccion::class, "Equipamiento.recepciones_transacciones", "id_recepcion", "id_transaccion" );
    }
}
