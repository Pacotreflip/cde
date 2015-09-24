<?php

namespace Ghi\Equipamiento\Recepciones;

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
     * Articulos relacionados con esta recepcion
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
     * Orden de compra asociada con esta recepcion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ordenCompra()
    {
        return $this->belongsTo(Transaccion::class, 'id_orden_compra', 'id_transaccion');
    }

    /**
     * Proveedor relacionado con esta recepcion
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empresa()
    {
        return $this->belongsTo(Proveedor::class, 'id_empresa', 'id_empresa');
    }
}
