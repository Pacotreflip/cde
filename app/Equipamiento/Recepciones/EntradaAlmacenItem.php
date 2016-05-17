<?php

namespace Ghi\Equipamiento\Recepciones;

use Illuminate\Database\Eloquent\Model;

class EntradaAlmacenItem extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'items';

    /**
     * @var string
     */
    protected $primaryKey = 'id_item';

    /**
     * @var array
     */
    protected $casts = [
        'cantidad' => 'float',
        'precio_unitario' => 'float'
    ];
    
    protected $fillable = [
        'id_antecedente',
        'item_antecedente',
        'id_material',
        'unidad',
        'numero',
        'cantidad',
        'cantidad_material',
        'saldo',
        'precio_unitario',
        'anticipo',
        'cantidad_original1',
        'estado',
        'importe',
        'id_almacen',
    ];
    public $timestamps = false;
    public function entrada_almacen()
    {
        return $this->belongsTo(EntradaAlmacen::class, 'id_transaccion', 'id_transaccion');
    }
    
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }
}
