<?php

namespace Ghi\Equipamiento\Areas;

use Ghi\Equipamiento\Moneda;
use Illuminate\Database\Eloquent\Model;
use Ghi\Equipamiento\Articulos\Material;

class MaterialRequerido extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'Equipamiento.materiales_requeridos';

    protected $fillable = [
        'id_material',
        'id_moneda',
        'cantidad_requerida',
        'precio_estimado',
        'cantidad_comparativa',
        'precio_comparativa',
        'id_moneda_comparativa',
        'existe_para_comparativa',
    ];

    protected $casts = [
        'existe_para_comparativa' => 'bool',
        'cantidad_requerida' => 'int',
        'precio_estimado' => 'float',
        'cantidad_comparativa' => 'int',
        'precio_comparativa' => 'float',
        'id_moneda' => 'int',
        'id_moneda_comparativa' => 'int',
    ];

    /**
     * Calcula el importe de este material requerido.
     * 
     * @return float
     */
    public function getImporteAttribute()
    {
        return $this->cantidad_requerida * $this->precio_estimado;
    }

    /**
     * Calcula el importe de comparativa de este material requerido.
     * 
     * @return float
     */
    public function getImporteComparativaAttribute()
    {
        return $this->cantidad_comparativa * $this->precio_comparativa;
    }

    /**
     * Obtiene el importe de acuerdo al tipo de cambio de la moneda homologada.
     * 
     * @param  float $tipo_cambio
     * @return float
     */
    public function getImporteEstimado($tipo_cambio)
    {
        if (! $this->moneda) {
            return 0;
        }
        
        if ($this->moneda->eslocal()) {
            return $this->importe / $tipo_cambio;
        }

        return $this->importe;
    }

    /**
     * Obtiene el precio estimado de acuerdo al tipo de cambio de la moneda homologada.
     * 
     * @param  float $tipo_cambio
     * @return float
     */
    public function getPrecioEstimado($tipo_cambio)
    {
        if (! $this->moneda) {
            return 0;
        }
        
        if ($this->moneda->eslocal()) {
            return $this->precio_estimado / $tipo_cambio;
        }

        return $this->precio_estimado;
    }

    /**
     * Obtiene el importe de comparativa de acuerdo al tipo de cambio de la moneda homologada.
     * 
     * @param  float $tipo_cambio
     * @return float
     */
    public function getImporteComparativa($tipo_cambio)
    {
        if (! $this->monedaComparativa) {
            return 0;
        }
        
        if ($this->monedaComparativa->eslocal()) {
            return $this->importe_comparativa / $tipo_cambio;
        }

        return $this->importe_comparativa;
    }

    /**
     * Obtiene el precio de comparativa de acuerdo al tipo de cambio de la moneda homologada.
     * 
     * @param  float $tipo_cambio
     * @return float
     */
    public function getPrecioComparativa($tipo_cambio)
    {
        if (! $this->monedaComparativa) {
            return 0;
        }
        
        if ($this->monedaComparativa->eslocal()) {
            return $this->precio_comparativa / $tipo_cambio;
        }

        return $this->precio_comparativa;
    }

    /**
     * Tipo de area a la que pertenece este material requerido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoArea()
    {
        return $this->belongsTo(Tipo::class, 'id_tipo_area');
    }

    /**
     * Material relacionado con este requerido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo [description]
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    /**
     * Moneda relacionada con este material requerido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'id_moneda', 'id_moneda');
    }

    /**
     * Moneda comparativa relacionada con este material requerido.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function monedaComparativa()
    {
        return $this->belongsTo(Moneda::class, 'id_moneda_comparativa', 'id_moneda');
    }
}
