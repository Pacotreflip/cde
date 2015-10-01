<?php

namespace Ghi\Equipamiento\Recepciones;

use Ghi\Core\Models\Obra;
use Ghi\Equipamiento\Areas\Area;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;
use Ghi\Equipamiento\Proveedores\Proveedor;
use Ghi\Equipamiento\Transacciones\Transaccion;

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
        'persona_recibe',
        'observaciones'
    ];

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
     * Articulos relacionados con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articulos()
    {
        return $this->belongsToMany(Material::class, 'Equipamiento.recepciones_materiales', 'id_recepcion', 'id_material')
            ->withPivot('cantidad', 'precio')
            ->withTimestamps();
    }

    /**
     * Orden de compra asociada con esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordenCompra()
    {
        return $this->belongsTo(Transaccion::class, 'id_orden_compra', 'id_transaccion');
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
     * Area de almacenamiento de los articulos de esta recepcion.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area_almacenamiento');
    }

    /**
     * Recibe un material en este folio de recepcion.
     * 
     * @param  Material $material
     * @param  float    $cantidad
     * @param  float    $precio
     * 
     * @return void
     */
    public function recibeMaterial(Material $material, $cantidad, $precio = 0)
    {
        $this->articulos()->save($material, ['cantidad' => $cantidad, 'precio' => $precio]);
    }
}
