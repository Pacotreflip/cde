<?php

namespace Ghi\Equipamiento\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'empresas';

    /**
     * @var string
     */
    protected $primaryKey = 'id_empresa';

    /**
     * @var array
     */
    protected $fillable = ['razon_social', 'rfc'];

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $casts = [
        'tipo_empresa' => 'int'
    ];

    /**
     * Crea el objeto de valor de tipo de proveedor
     * 
     * @param  int $value
     * @return Tipo
     */
    public function getTipoEmpresaAttribute($value)
    {
        return new Tipo($value);
    }

    /**
     * Scope para filtrar las empresas que son proveedores y contratistas
     * @param  Builder $query
     * @return Builder
     */
    public function scopeSoloProveedores($query)
    {
        $tipos = [
            Tipo::PROVEEDOR,
            Tipo::PROVEEDOR_MATERIALES,
            Tipo::CONTRATISTA,
            Tipo::PROVEEDOR_MATERIALES_CONTRATISTA
        ];

        return $query->whereIn('tipo_empresa', $tipos);
    }
}
