<?php

namespace Ghi\Domain\Core;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
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
     * @var bool
     */
    public $timestamps = false;

    /**
     * Transaccion relacionada con este item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaccion()
    {
        return $this->belongsTo(Transaccion::class, 'id_transaccion', 'id_transaccion');
    }
}
