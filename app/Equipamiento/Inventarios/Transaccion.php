<?php

namespace Ghi\Equipamiento\Inventarios;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    /**
     * @var string
     */
    protected $connection = 'cadeco';

    /**
     * @var string
     */
    protected $table = 'Equipamiento.transacciones';

    /**
     * Transaccion especializada.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function transaccion()
    {
        return $this->morphTo();
    }
}
