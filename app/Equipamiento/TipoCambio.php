<?php

namespace Ghi\Equipamiento;

use Ghi\Equipamiento\Moneda;
use Illuminate\Database\Eloquent\Model;

class TipoCambio extends Model
{
    protected $connection = 'cadeco';

    protected $table = 'cambios';

    protected $dates = ['fecha'];

    public $timestamps = false;

    /**
     * Moneda relacionada con este tipo de cambio.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'id_moneda', 'id_moneda');
    }

    /**
     * Obtiene el tipo de cambio mas reciente de una moneda.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $id_moneda
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMasReciente($query, $id_moneda)
    {
        return $query->where('id_moneda', $id_moneda)
            ->orderBy('fecha', 'DESC')
            ->first();
    }
}
