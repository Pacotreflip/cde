<?php

namespace Ghi\Equipamiento;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'monedas';

    /**
     * @var string
     */
    protected $primaryKey = 'id_moneda';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indica si esta moneda es de uso local.
     * 
     * @return bool
     */
    public function esLocal()
    {
        return (bool) $this->tipo;
    }

    /**
     * Tipos de cambio de esta moneda.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tiposCambio()
    {
        return $this->hasMany(TipoCambio::class, 'id_moneda', 'id_moneda');
    }

    /**
     * Tipo de cambio mas reciente de esta moneda.
     * 
     * @return \Ghi\Equipamiento\TipoCambio
     */
    public function tipoCambioMasReciente()
    {
        if ($this->esLocal()) {
            return 1;
        }
        
        return $this->tiposCambio()->orderBy('fecha', 'DESC')->first();
    }
}
